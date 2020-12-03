<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeclineOwner extends Mailable
{
    use Queueable, SerializesModels;

    protected $receiver;
    protected $decline_reasons;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($receiver, $decline_reasons)
    {
        $this->receiver = $receiver;
        $this->decline_reasons = $decline_reasons;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Từ chối đăng ký chủ trọ";
        return $this->view('mail.decline-owner')
                ->with('subject', $subject)
                ->with('receiver', $this->receiver)
                ->with('decline_reasons', $this->decline_reasons);
    }
}
