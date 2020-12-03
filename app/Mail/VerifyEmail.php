<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $receiver;
    protected $code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($receiver, $code)
    {
        $this->receiver = $receiver;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Mã xác minh tài khoản";
        return $this->view('mail.verify-email')
                ->with('subject', $subject)
                ->with('receiver', $this->receiver)
                ->with('code', $this->code);
    }
}
