<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Event;
use App\Notifications\DailyCareTaskNotification;

class GenerateDailyTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:daily-notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily task notifications to users with pending tasks for today.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->format('Y-m-d');
        
        // Find all users who have pending tasks today
        $users = User::whereHas('gardens.plants.events', function ($query) use ($today) {
            $query->whereDate('scheduled_date', $today)
                  ->where('status', 'PENDING');
        })->with(['gardens.plants.events' => function ($query) use ($today) {
            $query->whereDate('scheduled_date', $today)
                  ->where('status', 'PENDING')
                  ->with('eventType');
        }, 'gardens.plants.plantTemplate'])->get();

        $count = 0;

        foreach ($users as $user) {
            $tasksByGarden = [];
            $totalTasks = 0;

            foreach ($user->gardens as $garden) {
                $gardenTasks = [];
                
                foreach ($garden->plants as $plant) {
                    foreach ($plant->events as $event) {
                        $type = 'other';
                        $code = $event->eventType->code ?? '';
                        if (str_contains($code, 'WATER')) $type = 'water';
                        elseif (str_contains($code, 'FERTILIZER')) $type = 'fertilizer';
                        
                        $gardenTasks[] = [
                            'plant_name' => $plant->plantTemplate->name_id ?? 'Tanaman',
                            'type' => $type,
                            'amount' => $plant->plantTemplate->water_requirement ?? 'secukupnya'
                        ];
                        $totalTasks++;
                    }
                }
                
                if (count($gardenTasks) > 0) {
                    $tasksByGarden[$garden->name] = $gardenTasks;
                }
            }

            if ($totalTasks > 0) {
                $user->notify(new DailyCareTaskNotification($tasksByGarden, $totalTasks));
                $count++;
            }
        }

        $this->info("Successfully sent daily task notifications to {$count} users.");
    }
}
