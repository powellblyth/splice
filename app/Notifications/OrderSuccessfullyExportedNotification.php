<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderSuccessfullyExportedNotification extends Notification {
    use Queueable;
    private $notificationData;
    private $supplier;

    /**
     * Create a new notification instance.
     * WalkerOrderExportFailed constructor.
     * @param string $supplier
     * @param array $successData
     * @return void
     */
    public function __construct(string $supplier, array $notificationData) {
        //
        $this->notificationData = $notificationData;
        $this->supplier = $supplier;
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
            ->success()
            ->subject('Orders awaiting processing by ' . $this->supplier . ' at ' . date('Y-m-d H:i'))
            ->line('Hey ' . $notifiable->name . ',')
            ->line('----')
            ->line('These are the recent successful orders which are exported but may not yet be despatched');
//        var_dump($this->errorData);die();
        foreach ($this->notificationData as $orderID => $error) {
            $message = $message->line($orderID . " - " . $error);
        }

        return $message->line($errors)
            ->line('')
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
