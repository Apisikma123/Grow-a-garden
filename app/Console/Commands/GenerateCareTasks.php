<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\Garden;
use App\Models\WeatherRule;
use App\Models\ActivityWeatherRule;
use App\Services\WeatherService;
use App\Services\AutopilotService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GenerateCareTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'care:generate-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily care tasks and apply weather rules';

    /**
     * Execute the console command.
     */
    public function handle(WeatherService $weatherService, AutopilotService $autopilot)
    {
        $this->info("Starting Daily Care Tasks Generation: " . now()->format('Y-m-d'));

        // 1. Extend the 14-day rolling window for all Active users (who have active plants)
        // For efficiency, we just grab all users who have active gardens/plants.
        // But to keep it simple, we can just run autopilot for all users.
        $users = \App\Models\User::has('gardens.plants')->get();
        foreach ($users as $user) {
            $autopilot->generateForUser($user);
        }
        $this->info("Autopilot rolling window extended.");

        // 2. Fetch today's pending tasks grouped by Garden
        $today = Carbon::today()->toDateString();
        $gardens = Garden::whereHas('plants.events', function ($q) use ($today) {
            $q->where('scheduled_date', $today)->where('status', 'PENDING');
        })->with(['plants.events' => function ($q) use ($today) {
            $q->where('scheduled_date', $today)->where('status', 'PENDING');
        }])->get();

        $activityRules = ActivityWeatherRule::where('is_active', true)->get();
        $weatherRules = WeatherRule::where('is_active', true)->get();

        $skippedCount = 0;
        $alertCount = 0;

        foreach ($gardens as $garden) {
            if (!$garden->latitude || !$garden->longitude) {
                continue; // Cannot fetch weather without coordinates
            }

            $weather = $weatherService->getTodayWeather($garden->latitude, $garden->longitude);
            if (!$weather) {
                $this->warn("Failed to fetch weather for Garden ID: {$garden->id}");
                continue;
            }

            // Check Activity Rules to modify tasks
            foreach ($garden->plants as $plant) {
                foreach ($plant->events as $event) {
                    $eventType = $event->eventType->code ?? '';
                    
                    foreach ($activityRules as $rule) {
                        // Check if rule applies to this event type
                        if (!$this->isActivityMatch($rule->activity_type, $eventType)) continue;

                        $currentValue = $weather[$rule->weather_field] ?? null;
                        if ($currentValue === null) continue;

                        if ($this->evaluateRule($currentValue, $rule->operator, $rule->threshold)) {
                            $event->status = ($rule->action === 'DITUNDA') ? 'SKIPPED' : 'PENDING';
                            $originalMsg = explode(" (Alasan:", $event->message)[0]; // strip old reason if any
                            $event->message = $originalMsg . " (Alasan: " . $rule->message . ")";
                            $event->save();
                            
                            if ($event->status === 'SKIPPED') {
                                $skippedCount++;
                            }
                            break; // Stop checking other activity rules for this event
                        }
                    }
                }
            }

            // Check Weather Rules to generate Alerts
            foreach ($weatherRules as $rule) {
                $currentValue = $weather[$rule->weather_field] ?? null;
                if ($currentValue === null) continue;

                if ($this->evaluateRule($currentValue, $rule->operator, $rule->threshold)) {
                    // Check if an alert already exists for this garden today from this rule
                    $exists = DB::table('alerts')
                        ->where('garden_id', $garden->id)
                        ->where('source_type', 'WEATHER_RULE')
                        ->where('source_id', $rule->id)
                        ->whereDate('triggered_at', $today)
                        ->exists();

                    if (!$exists) {
                        DB::table('alerts')->insert([
                            'garden_id' => $garden->id,
                            'source_type' => 'WEATHER_RULE',
                            'source_id' => $rule->id,
                            'severity' => $rule->severity,
                            'message' => $rule->message . " (Tercatat: {$currentValue})",
                            'status' => 'ACTIVE',
                            'triggered_at' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $alertCount++;
                    }
                }
            }
        }

        $this->info("Daily tasks evaluated. Skipped: {$skippedCount} tasks. Generated {$alertCount} alerts.");
    }

    private function isActivityMatch(string $activityType, string $eventTypeCode): bool
    {
        $type = strtolower($activityType);
        $code = strtolower($eventTypeCode);
        
        if (str_contains($type, 'water') && str_contains($code, 'water')) return true;
        if (str_contains($type, 'fertiliz') && str_contains($code, 'fertilizer')) return true;
        if (str_contains($type, 'prun') && str_contains($code, 'pruning')) return true;
        if (str_contains($type, 'pest') && str_contains($code, 'pest')) return true;
        
        return false;
    }

    private function evaluateRule($value, string $operator, $threshold): bool
    {
        return match ($operator) {
            '>' => $value > $threshold,
            '<' => $value < $threshold,
            '>=' => $value >= $threshold,
            '<=' => $value <= $threshold,
            '==' => $value == $threshold,
            '!=' => $value != $threshold,
            default => false,
        };
    }
}
