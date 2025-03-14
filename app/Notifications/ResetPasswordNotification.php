<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Restablecer tu Contraseña')->greeting(' Hola')
                    ->line('Hemos recibido una solicitud para restablecer tu contraseña.')
                    ->action('Restablecer Contraseña', url('password/reset/'.$this->token.'?email='.urlencode($notifiable->email)))
                    ->line('Si no solicitaste un restablecimiento de contraseña, no es necesario realizar ninguna otra acción.'); 
    }
}
