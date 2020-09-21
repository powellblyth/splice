<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UnprocessedOrdersNotification extends Notification {
    use Queueable;

    private $notificationData;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(array $notificationData) {
        $this->notificationData = $notificationData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable) {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable) {
        $errors = '';
        $message = new MailMessage();
        $message = $message
            ->error()
            ->subject('Founds some unprocessed orders ' . date('Y-m-d H:i'))
            ->line('Hey ' . $notifiable->name . ',')
            ->line('----')
            ->line('These orders may not have been processed by Walker yet. Maybe they will be processed tomorrow?');

        foreach ($this->notificationData as $dataItem) {
            $message = $message->line($dataItem->order_number . " - ordered on " . $dataItem->order_date);
        }

        return $message->line($errors)
            ->line('Hopefully this info helps.')
            ->line('---')
            ->line('Regards, The System');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable) {
        return [
            //
        ];
    }
}
