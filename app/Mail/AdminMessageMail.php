<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectLine;
    public $bodyMessage;
    public $recipientName;

    public function __construct($subjectLine, $bodyMessage, $recipientName = null)
    {
        $this->subjectLine = $subjectLine;
        $this->bodyMessage = $bodyMessage;
        $this->recipientName = $recipientName;
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
                    ->view('emails.admin-message');
    }
}
