<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class importFromSupplierErrorNotification extends Notification
{
    private $notificationData;
    private $supplier;
    private $subject;

    /**
     * Create a new notification instance.
     * WalkerOrderExportFailed constructor.
     * @param string $supplier
     * @param string $subject
     * @param array $successData
     * @return void
     */
    public function __construct(string $supplier, string $subject, array $notificationData)
    {
        $this->notificationData = $notificationData;
        $this->supplier         = $supplier;
        $this->subject          = $subject;
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
            ->subject('Error importing ' . $this->subject . 's from ' . $this->supplier . ' at ' . date('Y-m-d H:i'))
            ->line('Hey ' . $notifiable->name . ',')
            ->line('----')
            ->line('Some ' . $this->subject . 's could not be imported from ' . $this->supplier . '. These will be retried');
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
