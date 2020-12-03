<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use App\Mail\ApproveOwner;
use App\Mail\DeclineOwner;

class MailServices
{
    public function __construct()
    {
        //
    }

    public function approveOwner($owner)
    {
        $mail = new ApproveOwner($owner);
        $this->send($owner->email, $mail);
    }

    public function declineOwner($user, $declineReasons)
    {
        $mail = new DeclineOwner($user, $declineReasons);
        $this->send($user->email, $mail);
    }

    public function verifyEmail($user, $code)
    {
        $mail = new VerifyEmail($user, $code);
        $this->send($user->email, $mail);
    }

    private function send($receiverEmail, $mail)
    {
        Mail::to($receiverEmail)->send($mail);
    }
}
