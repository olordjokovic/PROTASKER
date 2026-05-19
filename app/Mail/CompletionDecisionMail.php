<?php

namespace App\Mail;

use App\Models\CompletionRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompletionDecisionMail extends Mailable
{
    use Queueable, SerializesModels;

    public CompletionRequest $completionRequest;
    public User $admin;
    public mixed $item;

    public function __construct(CompletionRequest $completionRequest, User $admin, mixed $item)
    {
        $this->completionRequest = $completionRequest;
        $this->admin = $admin;
        $this->item = $item;
    }

    public function build()
    {
        return $this
            ->subject('Decisión sobre tu solicitud - ProTasker')
            ->view('emails.completion-decision');
    }
}