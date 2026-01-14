<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignmentGraded extends Notification
{
    use Queueable;

    protected $submission;

    /**
     * Create a new notification instance.
     */
    public function __construct($submission)
    {
        $this->submission = $submission;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'grade',
            'title' => 'งานตรวจแล้ว',
            'message' => 'งาน ' . ($this->submission->assignment->title ?? 'Untitled') . ' ได้รับการตรวจแล้ว ได้ ' . $this->submission->score . ' คะแนน',
            'link' => route('typing.student.grades'),
            'icon' => 'fas fa-star',
            'color' => 'bg-yellow-100 text-yellow-600',
        ];
    }
}
