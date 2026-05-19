<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;
    public string $userName;

    /*
     * Crea una nueva instancia del correo.
     */
    public function __construct(string $code, string $userName)
    {
        $this->code = $code;
        $this->userName = $userName;
    }

    
    public function build(): self
    {
        return $this->subject('Codigo de recuperacion de ProTasker')
            ->view('emails.password-reset-code');
    }
}