<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrdersDespatchedNotification extends Notification
{
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
    public function toMail($notifiable): MailMessage
    {
        $errors  = '';
        $message = new MailMessage();
        $message = $message
            ->success()
            ->subject('Orders Marked as Despatched by ' . $this->supplier . ' at ' . date('Y-m-d H:i'))
            ->line('Hey ' . $notifiable->name . ',')
            ->line('----')
            ->line('These are the recent despatched orders from ' . $this->supplier);

        foreach ($this->notificationData as $fileName => $orderInfo) {
            $message = $message->line("File - " . $fileName)->line("-------------------");
            foreach ($orderInfo as $orderID => $error) {
                $message = $message->line($orderID . " - " . $error);
            }
        }

        return $message->line($errors)
            ->action('Update orders in Unleashed', url('https://au.unleashedsoftware.com/v2/SalesOrder/List#status=Placed,warehouse=24,exactMatchFilter=false'))
            ->line('Please adjust the despatched flag in Unleashed')
            ->line('---')
            ->line('Regards, The worst bit of the integration of the System');
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