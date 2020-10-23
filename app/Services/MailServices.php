<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;

class MailServices
{
    public function __construct()
    {
        //
    }

    public function forgotPassword($user, $code)
    {
        $mail = new ForgotPassword($user, $code);
        Mail::to($user->email)->send($mail);
        return true;
    }
}
