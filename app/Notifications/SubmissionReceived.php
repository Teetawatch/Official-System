<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubmissionReceived extends Notification
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
            'type' => 'submission',
            'title' => 'มีงานส่งใหม่',
            'message' => 'นักเรียน ' . ($this->submission->user->name ?? 'ไม่ระบุ') . ' ได้ส่งงาน ' . ($this->submission->assignment->title ?? 'ไม่ระบุ'),
            'link' => route('typing.admin.submissions'),
            'icon' => 'fas fa-file-upload',
            'color' => 'bg-blue-100 text-blue-600',
        ];
    }
}
