<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class FilamentNewUserNotification extends Notification
{
    use Queueable;

    public function __construct(public string $token, public string $url, public User $user) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(Lang::get('Welcome to Support Center - Your Account is Ready!'))
            ->greeting(Lang::get('Hello '. $this->user->name .'!'))
            ->line(Lang::get('An administrator has created a Support Center account for you.'))
            ->line(Lang::get('Your login email is: **' . $this->user->email . '**'))
            ->line(Lang::get('To get started, please set your password by clicking the button below:'))
            ->action(Lang::get('Set Your Password'), $this->url)
            ->line(Lang::get('This password setup link will expire in :count minutes.', [
                'count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')
            ]))
            ->line(Lang::get('If you did not expect to receive this email, please contact your system administrator.'));
    }
}
