<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Ichtrojan\Otp\Otp;
class ResetPasswordVerificationNotification extends Notification
{
    use Queueable;

    public $message;
    public $subject;
    public $fromEmail;
    public $mailer;
    private $otp;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->message = 'This is your code to reset your password';
        $this->subject = 'Mail from Smart Guidance System';
        $this->fromEmail = 'saeedsayed077@gmail.com';
        $this->mailer = 'smtp';
        $this->otp = new Otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $otp = $this->otp->generate($notifiable->email,'numeric', 5,15);

        return (new MailMessage)
            ->mailer($this->mailer)
            ->from($this->fromEmail, 'Smart Guidance System')
            ->subject($this->subject)
            ->greeting('Hello ' . $notifiable->username)
            ->line($this->message)
            ->line('Code: ' . $otp->token);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
