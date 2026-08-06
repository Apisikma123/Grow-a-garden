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
                'scheduled_date' => $scheduledDate,
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
     * Generates tasks for the next 14 days from today.
     */
    private function generateMaintenanceEvents(Plant $plant, $template, Carbon $plantedDate): int
    {
        $careRules = $template->care_rules;
        if (!$careRules || !is_array($careRules)) {
            // If no care_rules defined, generate basic default tasks
            return $this->generateDefaultMaintenanceTasks($plant, $template, $plantedDate);
        }

        $generated = 0;
        $today = Carbon::today();
        $endDate = $today->copy()->addDays(14);
        $hst = (int) now()->diffInDays($plantedDate);

        // Parse care rules and generate events
        foreach ($careRules as $key => $ruleDescription) {
            $eventTypeCode = $this->mapRuleKeyToEventType($key);
            $eventType = EventType::where('code', $eventTypeCode)->first();
            if (!$eventType) continue;

            $intervalDays = $this->parseIntervalFromRule($key, $ruleDescription);

            // Generate events at the parsed interval
            $currentDate = $today->copy();
            while ($currentDate->lte($endDate)) {
                // Check this task doesn't already exist
                $exists = Event::where('plant_id', $plant->id)
                    ->where('event_type_id', $eventType->id)
                    ->where('scheduled_date', $currentDate->toDateString())
                    ->exists();

                if (!$exists) {
                    Event::create([
                        'plant_id' => $plant->id,
                        'event_type_id' => $eventType->id,
                        'scheduled_date' => $currentDate->copy(),
                        'status' => 'PENDING',
                        'priority' => $eventType->default_priority ?? 'MEDIUM',
                        'message' => "{$template->name_id}: {$eventType->label}",
                    ]);
                    $generated++;
                }

                $currentDate->addDays($intervalDays);
            }
        }

        return $generated;
    }

    /**
     * Generate default maintenance tasks when no care_rules are defined.
     * Default: water every day, fertilize every 7 days, pest check every 14 days.
     */
    private function generateDefaultMaintenanceTasks(Plant $plant, $template, Carbon $plantedDate): int
    {
        $generated = 0;
        $today = Carbon::today();
        $endDate = $today->copy()->addDays(14);

        $defaults = [
            'WATERING_REMINDER' => 1,   // Every day
            'FERTILIZER_REMINDER' => 7, // Every 7 days
            'PEST_INSPECTION' => 14,    // Every 14 days
        ];

        foreach ($defaults as $code => $intervalDays) {
            $eventType = EventType::where('code', $code)->first();
            if (!$eventType) continue;

            $currentDate = $today->copy();
            while ($currentDate->lte($endDate)) {
                $exists = Event::where('plant_id', $plant->id)
                    ->where('event_type_id', $eventType->id)
                    ->where('scheduled_date', $currentDate->toDateString())
                    ->exists();

                if (!$exists) {
                    Event::create([
                        'plant_id' => $plant->id,
                        'event_type_id' => $eventType->id,
                        'scheduled_date' => $currentDate->copy(),
                        'status' => 'PENDING',
                        'priority' => $eventType->default_priority ?? 'MEDIUM',
                        'message' => "{$template->name_id}: {$eventType->label}",
                    ]);
                    $generated++;
                }

                $currentDate->addDays($intervalDays);
            }
        }

        return $generated;
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
        if (str_contains($key, 'pest') || str_contains($key, 'hama')) return 14;
        if (str_contains($key, 'prun') || str_contains($key, 'pangkas')) return 14;

        return 7; // Default fallback
    }
}
