<?php

namespace App\Mail;

use App\Models\CompletionRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompletionRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public CompletionRequest $completionRequest;
    public User $sender;
    public mixed $item;

    public function __construct(CompletionRequest $completionRequest, User $sender, mixed $item)
    {
        $this->completionRequest = $completionRequest;
        $this->sender = $sender;
        $this->item = $item;
    }

    public function build()
    {
        return $this
            ->subject('Nueva solicitud de finalización - ProTasker')
            ->view('emails.completion-request');
    }
}