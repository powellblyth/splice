<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderFailedExportNotification extends Notification
{
    use Queueable;

    private $notificationData;
    private $supplier;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $supplier, array $notificationData)
    {
        $this->notificationData = $notificationData;
        $this->supplier         = $supplier;
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
    public function toMail($notifiable)
    {
        $errors  = '';
        $message = new MailMessage();
        $message = $message
            ->error()
            ->subject('Error exporting some orders to ' . $this->supplier . ' at ' . date('Y-m-d H:i'))
            ->line('Hey ' . $notifiable->name . ',')
            ->line('----')
            ->line('Some orders have failed to be sent to ' . $this->supplier);
//        var_dump($this->errorData);die();
        foreach ($this->notificationData as $orderID => $error) {
            $message = $message->line($orderID . " - " . $error);
        }

        return $message->line($errors)
            ->action('Update orders in Unleashed', url('https://au.unleashedsoftware.com/v2/SalesOrder/List#status=Placed,warehouse=24,exactMatchFilter=false'))
            ->line('If this is a missing data issue, once you fix this, the order should retry automatically. If not the system will just retry every hour')
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
