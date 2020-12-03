<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApproveOwner extends Mailable
{
    use Queueable, SerializesModels;

    protected $receiver;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($receiver)
    {
        $this->receiver = $receiver;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Chấp nhận đăng ký chủ trọ";
        return $this->view('mail.approve-owner')
                ->with('subject', $subject)
                ->with('receiver', $this->receiver);
    }
}
