<?php

namespace App\Notifications;

use App\Mail\DailyDigestMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class DailyCareTaskNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    protected $tasksByGarden;
    protected $totalTasks;

    /**
     * Create a new notification instance.
     */
    public function __construct($tasksByGarden, $totalTasks)
    {
        $this->tasksByGarden = $tasksByGarden;
        $this->totalTasks = $totalTasks;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database', 'broadcast'];
        
        // Only send email if user enabled it (assuming email_notifications column exists, defaults to true)
        if ($notifiable->email_notifications ?? true) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        return (new DailyDigestMail($notifiable, $this->tasksByGarden))
            ->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Tugas Perawatan Hari Ini 🌿',
            'message' => 'Ada ' . $this->totalTasks . ' tugas perawatan kebun yang menunggu.',
            'action_url' => url('/tasks'),
            'type' => 'daily_task'
        ];
    }
    
    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'Tugas Perawatan Hari Ini 🌿',
            'message' => 'Ada ' . $this->totalTasks . ' tugas perawatan kebun yang menunggu.',
            'action_url' => url('/tasks'),
            'type' => 'daily_task',
            'created_at' => now()->toIso8601String(),
        ]);
    }
}
