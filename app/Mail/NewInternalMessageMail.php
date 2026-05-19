<?php

namespace App\Mail;

use App\Models\Message;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewInternalMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public $messageData;
    public $sender;
    public $receiver;

    public function __construct(
        Message $messageData,
        User $sender,
        User $receiver
    ) {
        $this->messageData = $messageData;
        $this->sender = $sender;
        $this->receiver = $receiver;
    }

    public function build()
    {
        return $this
            ->subject('Nuevo mensaje interno - ProTasker')
            ->view('emails.internal-message');
    }
}