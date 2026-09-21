<?php

namespace App\Services;

use App\Models\Plant;
use App\Models\Event;
use App\Models\EventType;
use App\Models\User;
use Carbon\Carbon;

class AutopilotService
{
    /**
     * Generate care tasks for all active plants belonging to a user.
     * Called when a user upgrades to a paid plan.
     */
    public function generateForUser(User $user): int
    {
        $plants = Plant::whereIn('garden_id', $user->gardens()->pluck('id'))
            ->where('status', 'ACTIVE')
            ->with('plantTemplate')
            ->get();

        if ($plants->isNotEmpty()) {
            // Remove/purge past overdue tasks that were missed or pending
            Event::whereIn('plant_id', $plants->pluck('id'))
                ->whereDate('scheduled_date', '<', Carbon::today())
                ->whereIn('status', ['PENDING', 'MISSED'])
                ->delete();
        }

        $totalGenerated = 0;

        foreach ($plants as $plant) {
            $totalGenerated += $this->generateForPlant($plant);
        }

        return $totalGenerated;
    }

    /**
     * Generate care tasks for a single plant based on its template's care_rules.
     */
    public function generateForPlant(Plant $plant): int
    {
        $template = $plant->plantTemplate;
        if (!$template) return 0;

        $plantedDate = $plant->planted_date;
        if (!$plantedDate) return 0;

        $generated = 0;

        // Generate lifecycle milestone events
        $generated += $this->generateLifecycleEvents($plant, $template, $plantedDate);

        // Generate maintenance care tasks based on care_rules
        $generated += $this->generateMaintenanceEvents($plant, $template, $plantedDate);

        return $generated;
    }

    /**
     * Generate lifecycle milestone events (germination, seedling, harvest, etc.)
     */
    private function generateLifecycleEvents(Plant $plant, $template, Carbon $plantedDate): int
    {
        $milestones = [
            'GERMINATION' => $template->germination_day,
            'SEEDLING' => $template->seedling_day,
            'VEGETATIVE' => $template->vegetative_day,
            'FLOWERING' => $template->flowering_day,
            'FRUITING' => $template->fruiting_day,
            'HARVEST_READY' => $template->harvest_start_day,
        ];

        $generated = 0;

        foreach ($milestones as $code => $day) {
            if (!$day) continue;

            $scheduledDate = $plantedDate->copy()->addDays($day);

            // Skip milestone if date is already in the past
            if ($scheduledDate->lt(Carbon::today())) continue;

            // Skip if event already exists for this plant/type/date
            $eventType = EventType::where('code', $code)->first();
            if (!$eventType) continue;

            $exists = Event::where('plant_id', $plant->id)
                ->where('event_type_id', $eventType->id)
                ->where('scheduled_date', $scheduledDate->toDateString())
                ->exists();

            if ($exists) continue;

            Event::create([
                'plant_id' => $plant->id,
                'event_type_id' => $eventType->id,
                'scheduled_date' => $scheduledDate->toDateString(),
                'status' => 'PENDING',
                'priority' => $eventType->default_priority ?? 'MEDIUM',
                'message' => "{$template->name_id}: {$eventType->label} (Hari ke-{$day})",
            ]);

            $generated++;
        }

        return $generated;
    }

    /**
     * Generate maintenance events (watering, fertilizing, etc.) based on care_rules.
     * Generates tasks for the next 14 days from today with strict spacing.
     */
    private function generateMaintenanceEvents(Plant $plant, $template, Carbon $plantedDate): int
    {
        $careRules = $template->care_rules;
        if (!$careRules || !is_array($careRules)) {
            return $this->generateDefaultMaintenanceTasks($plant, $template, $plantedDate);
        }

        $generated = 0;
        $today = Carbon::today();
        $endDate = $today->copy()->addDays(14);
        $hst = max(0, (int) $today->diffInDays($plantedDate));

        foreach ($careRules as $key => $ruleDescription) {
            $eventTypeCode = $this->mapRuleKeyToEventType($key);
            $eventType = EventType::where('code', $eventTypeCode)->first();
            if (!$eventType) continue;

            $intervalDays = max(1, $this->parseIntervalFromRule($key, $ruleDescription, $template));
            $generated += $this->scheduleEventsForType($plant, $template, $eventType, $intervalDays, $plantedDate, $today, $endDate);
        }

        return $generated;
    }

    /**
     * Generate default maintenance tasks when no care_rules are defined.
     * Default: water daily, fertilize every 7-10 days, pest check every 7 days.
     */
    private function generateDefaultMaintenanceTasks(Plant $plant, $template, Carbon $plantedDate): int
    {
        $generated = 0;
        $today = Carbon::today();
        $endDate = $today->copy()->addDays(14);

        // Calculate realistic watering interval based on plant type / water needs
        $waterInterval = $this->resolveWateringInterval($template);

        $defaults = [
            'WATERING_REMINDER' => $waterInterval, // 2-3 days (avoids root rot/overwatering)
            'FERTILIZER_REMINDER' => 7,            // Every 7 days
            'PEST_INSPECTION' => 7,               // Every 7 days
            'WEEDING' => 12,                      // Every 12 days
        ];

        foreach ($defaults as $code => $intervalDays) {
            $eventType = EventType::where('code', $code)->first();
            if (!$eventType) continue;

            $generated += $this->scheduleEventsForType($plant, $template, $eventType, $intervalDays, $plantedDate, $today, $endDate);
        }

        return $generated;
    }

    /**
     * Smart scheduler ensuring no duplicate/consecutive tasks and realistic spacing.
     */
    private function scheduleEventsForType(Plant $plant, $template, EventType $eventType, int $intervalDays, Carbon $plantedDate, Carbon $today, Carbon $endDate): int
    {
        $generated = 0;
        $code = $eventType->code;

        // Find the latest scheduled event of this type
        $latestEvent = Event::where('plant_id', $plant->id)
            ->where('event_type_id', $eventType->id)
            ->orderBy('scheduled_date', 'desc')
            ->first();

        if ($latestEvent) {
            $currentDate = Carbon::parse($latestEvent->scheduled_date)->addDays($intervalDays);
            // If the calculated next date is in the past, advance it forward in steps of intervalDays
            while ($currentDate->lt($today)) {
                $currentDate->addDays($intervalDays);
            }
        } else {
            // First time scheduling: offset initial date based on task nature
            $initialOffset = match ($code) {
                'WATERING_REMINDER' => 0,
                'PEST_INSPECTION' => 5,
                'FERTILIZER_REMINDER' => 7,
                'WEEDING' => 12,
                'PRUNING' => 16,
                default => 0,
            };

            $currentDate = $plantedDate->copy()->addDays($initialOffset);
            while ($currentDate->lt($today)) {
                $currentDate->addDays($intervalDays);
            }
        }

        while ($currentDate->lte($endDate)) {
            $exists = Event::where('plant_id', $plant->id)
                ->where('event_type_id', $eventType->id)
                ->where('scheduled_date', $currentDate->toDateString())
                ->exists();

            if (!$exists) {
                $taskHst = max(0, (int) $currentDate->diffInDays($plantedDate));
                $message = $this->buildRichTaskMessage($code, $template, $taskHst);

                Event::create([
                    'plant_id' => $plant->id,
                    'event_type_id' => $eventType->id,
                    'scheduled_date' => $currentDate->toDateString(),
                    'status' => 'PENDING',
                    'priority' => $eventType->default_priority ?? 'MEDIUM',
                    'message' => $message,
                ]);
                $generated++;
            }

            $currentDate->addDays($intervalDays);
        }

        return $generated;
    }

    /**
     * Build informative, educational, and diverse task quest messages based on plant HST.
     */
    private function buildRichTaskMessage(string $code, $template, int $hst): string
    {
        $plantName = $template->name_id;

        return match ($code) {
            'WATERING_REMINDER' => "{$plantName}: Siram Air Secukupnya (Periksa Tanah Dulu - Tunda Bila Hari Hujan)",
            'FERTILIZER_REMINDER' => match (true) {
                $hst <= 12 => "{$plantName}: Beri Pupuk Awal (Kompos Cair / NPK Daun Encer - Tunda Bila Hujan Lebat)",
                $hst <= 25 => "{$plantName}: Pemupukan Masa Pertumbuhan Daun & Batang (NPK Seimbang)",
                default => "{$plantName}: Pemupukan Masa Pembungaan & Buah (Pupuk Fosfor & Kalium)",
            },
            'PEST_INSPECTION' => match (true) {
                $hst <= 10 => "{$plantName}: Cek Kutu Daun (Aphids) & Kesehatan Pangkal Batang Bibit",
                $hst <= 22 => "{$plantName}: Cek Bagian Bawah Daun dari Ulat & Kutu Kebul",
                default => "{$plantName}: Cek Gejala Kutu Putih, Ulat Buah & Jamur Daun",
            },
            'WEEDING' => "{$plantName}: Cabut Rumput Liar (Gulma) & Gemburkan Tanah",
            'PRUNING' => "{$plantName}: Pangkas Daun Kuning Bagian Bawah & Tunas Air Liar",
            'STAKING' => "{$plantName}: Pasang & Rapikan Ikatan Tiang Penyangga (Ajir)",
            'DRAINAGE_CHECK' => "{$plantName}: Cek Saluran Air / Lubang Pot (Pastikan Air Tidak Tergenang)",
            'FUNGUS_CHECK' => "{$plantName}: Cek Gejala Jamur Bercak Daun (Terutama di Cuaca Lembap)",
            'NEEM_SPRAY' => "{$plantName}: Semprot Cairan Hama Alami / Nabati di Sore Hari",
            default => "{$plantName}: {$code}",
        };
    }

    /**
     * Resolve realistic watering interval (in days) based on plant characteristics.
     * Prevents overwatering & root rot:
     * - Low water requirement / Succulent / Herbs: 4-5 days
     * - Leafy greens / High humidity needs: 2 days
     * - Standard vegetables (default): 2-3 days
     */
    private function resolveWateringInterval($template): int
    {
        if (!$template) return 2;

        $waterReq = strtolower($template->water_requirement ?? '');
        $catName = strtolower($template->category->name ?? '');
        $plantName = strtolower($template->name_id ?? '');

        // Check explicit textual hints in water_requirement
        if (str_contains($waterReq, 'rendah') || str_contains($waterReq, 'low') || str_contains($waterReq, 'kering')) {
            return 4;
        }

        // Fast-transpiring leafy greens in tropical sun (e.g. bayam, pakcoy, kangkung) need water every 2 days
        if (str_contains($plantName, 'kangkung') || str_contains($plantName, 'bayam') || str_contains($plantName, 'pakcoy') || str_contains($plantName, 'selada')) {
            return 2;
        }

        // Fruiting vegetables (cabai, tomat, terung) prefer deep watering every 2-3 days to encourage deep roots
        if (str_contains($plantName, 'cabai') || str_contains($plantName, 'tomat') || str_contains($plantName, 'terung')) {
            return 3;
        }

        return 2;
    }

    /**
     * Map a care_rules key to an EventType code.
     */
    private function mapRuleKeyToEventType(string $key): string
    {
        $key = strtolower($key);

        if (str_contains($key, 'water') || str_contains($key, 'siram')) {
            return 'WATERING_REMINDER';
        }
        if (str_contains($key, 'fertilizer') || str_contains($key, 'pupuk')) {
            return 'FERTILIZER_REMINDER';
        }
        if (str_contains($key, 'prun') || str_contains($key, 'pangkas') || str_contains($key, 'rempel')) {
            return 'PRUNING';
        }
        if (str_contains($key, 'pest') || str_contains($key, 'hama')) {
            return 'PEST_INSPECTION';
        }
        if (str_contains($key, 'stak') || str_contains($key, 'ajir')) {
            return 'STAKING';
        }
        if (str_contains($key, 'weed') || str_contains($key, 'gulma')) {
            return 'WEEDING';
        }
        if (str_contains($key, 'drain')) {
            return 'DRAINAGE_CHECK';
        }
        if (str_contains($key, 'fungus') || str_contains($key, 'jamur')) {
            return 'FUNGUS_CHECK';
        }
        if (str_contains($key, 'neem') || str_contains($key, 'pestisida')) {
            return 'NEEM_SPRAY';
        }

        return 'WATERING_REMINDER'; // Fallback
    }

    /**
     * Parse a rough interval (in days) from the care rule description.
     * e.g. "2-3 hari sekali" → 2, "Setiap 7 hari" → 7, "Setiap 14 hari" → 14
     */
    private function parseIntervalFromRule(string $key, string $description, $template = null): int
    {
        $desc = strtolower($description);
        $key = strtolower($key);

        // Check for range pattern e.g. "2-3 hari" or "2 - 3 hari"
        if (preg_match('/(\d+)\s*[-–]\s*(\d+)\s*hari/i', $desc, $matches)) {
            return (int) $matches[1];
        }

        // Check for "setiap X hari" pattern
        if (preg_match('/setiap\s+(\d+)\s*hari/i', $desc, $matches)) {
            return (int) $matches[1];
        }

        // Check for "every X days" pattern
        if (preg_match('/every\s+(\d+)\s*day/i', $desc, $matches)) {
            return (int) $matches[1];
        }

        // Check for "Xhari" pattern (avoid false positive on "2x sehari" by ensuring not followed by 'sehari')
        if (preg_match('/(\d+)\s*hari(?!\s*sehari)/i', $desc, $matches)) {
            return (int) $matches[1];
        }

        // Defaults based on key type (minimum 2 days for watering to avoid rot)
        if (str_contains($key, 'water') || str_contains($key, 'siram')) {
            return $this->resolveWateringInterval($template);
        }
        if (str_contains($key, 'fertilizer') || str_contains($key, 'pupuk')) return 7;
        if (str_contains($key, 'pest') || str_contains($key, 'hama')) return 7;
        if (str_contains($key, 'prun') || str_contains($key, 'pangkas')) return 14;
        if (str_contains($key, 'stak') || str_contains($key, 'ajir')) return 14;
        if (str_contains($key, 'weed') || str_contains($key, 'gulma')) return 12;
        if (str_contains($key, 'drain')) return 7;
        if (str_contains($key, 'fungus') || str_contains($key, 'jamur')) return 10;
        if (str_contains($key, 'neem') || str_contains($key, 'pestisida')) return 14;

        return 7; // Default fallback
    }
}
