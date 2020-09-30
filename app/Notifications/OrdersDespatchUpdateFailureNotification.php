<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrdersDespatchUpdateFailureNotification extends Notification {
    use Queueable;

    private $notificationData;
    private $supplier;

    /**
     * Create a new notification instance.
     * WalkerOrderExportFailed constructor.
     * @param string $supplier
     * @param array $notificationData
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
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage {
        $errors = '';
        $message = new MailMessage();
        $message = $message
            ->error()
            ->subject('Error marking despatched orders by ' . $this->supplier . ' at ' . date('Y-m-d H:i'))
            ->line('Hey ' . $notifiable->name . ',')
            ->line('----')
            ->line('Some orders have been despatched, but CR order system was unable to record this');
//        var_dump($this->errorData);die();
        foreach ($this->notificationData as $orderID => $error) {
            $message = $message->line($orderID . " - " . $error);
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
