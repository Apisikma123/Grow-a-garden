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
                'status' => $scheduledDate->isPast() ? 'MISSED' : 'PENDING',
                'priority' => $eventType->default_priority ?? 'MEDIUM',
                'message' => "{$template->name_id}: {$eventType->label} (HST {$day})",
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

            $intervalDays = max(1, $this->parseIntervalFromRule($key, $ruleDescription));
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

        $defaults = [
            'WATERING_REMINDER' => 1,    // Every day
            'FERTILIZER_REMINDER' => 7,  // Every 7 days
            'PEST_INSPECTION' => 7,     // Every 7 days
            'WEEDING' => 12,            // Every 12 days
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
            'WATERING_REMINDER' => "{$plantName}: Penyiraman Rutin Pagi & Sore",
            'FERTILIZER_REMINDER' => match (true) {
                $hst <= 12 => "{$plantName}: Nutrisi Awal Pembibitan (Pupuk Organik / NPK Daun Encer)",
                $hst <= 25 => "{$plantName}: Nutrisi Fase Vegetatif (Pembentukan Daun & Batang Kokoh)",
                default => "{$plantName}: Nutrisi Bobot Panen & Pembungaan (Tinggi Fosfor & Kalium)",
            },
            'PEST_INSPECTION' => match (true) {
                $hst <= 10 => "{$plantName}: Inspeksi Kutu Daun (Aphids) & Pangkal Batang Bibit",
                $hst <= 22 => "{$plantName}: Inspeksi Ulat Grayak & Daun Berlubang",
                default => "{$plantName}: Inspeksi Kutu Putih (Mealybugs) & Cek Jamur Daun",
            },
            'WEEDING' => "{$plantName}: Penyiangan Gulma & Penggemburan Media Tanam",
            'PRUNING' => "{$plantName}: Sanitasi Daun Kuning & Perempelan Tunas Air",
            'STAKING' => "{$plantName}: Pemasangan & Pengikatan Ajir Penyangga",
            default => "{$plantName}: {$code}",
        };
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

        return 'WATERING_REMINDER'; // Fallback
    }

    /**
     * Parse a rough interval (in days) from the care rule description.
     * e.g. "2x sehari" → 1, "Setiap 7 hari" → 7, "Setiap 14 hari" → 14
     */
    private function parseIntervalFromRule(string $key, string $description): int
    {
        $desc = strtolower($description);
        $key = strtolower($key);

        // Check for "setiap X hari" pattern
        if (preg_match('/setiap\s+(\d+)\s*hari/i', $desc, $matches)) {
            return (int) $matches[1];
        }

        // Check for "every X days" pattern
        if (preg_match('/every\s+(\d+)\s*day/i', $desc, $matches)) {
            return (int) $matches[1];
        }

        // Check for "Xhari" pattern
        if (preg_match('/(\d+)\s*hari/i', $desc, $matches)) {
            return (int) $matches[1];
        }

        // Defaults based on key type
        if (str_contains($key, 'water') || str_contains($key, 'siram')) return 1;
        if (str_contains($key, 'fertilizer') || str_contains($key, 'pupuk')) return 7;
        if (str_contains($key, 'pest') || str_contains($key, 'hama')) return 7;
        if (str_contains($key, 'prun') || str_contains($key, 'pangkas')) return 14;

        return 7; // Default fallback
    }
}
