<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class importOrderConfirmsFromSupplierNotification extends Notification
{
    use Queueable;

    private $notificationData;
    private $despatcher;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $despatcher, array $notificationData)
    {
        $this->notificationData = $notificationData;
        $this->despatcher       = $despatcher;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $errors  = '';
        $message = new MailMessage();
        $message = $message
            ->error()
            ->subject('Error importing order confirmations from ' . $this->despatcher . ' at ' . date('Y-m-d H:i'))
            ->line('Hey ' . $notifiable->name . ',')
            ->line('----')
            ->line('Some orders below were not able to be updated confirming the status from the despatcher');
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
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
