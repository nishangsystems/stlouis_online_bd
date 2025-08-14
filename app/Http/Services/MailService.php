<?php

namespace App\Http\Services;

use App\Mail\NotifyMail;
use Illuminate\Support\Facades\Mail;

class MailService{
    public function __construct(){
    }

    public function sendPlainMail($subject, $message, $data=[])//data['name', 'email']
    {
        # code...
        // Mail::to($data['email'])->send(new NotifyMail($subject, $message, $data));
    }

    public function sendMailWithFile()
    {
        # code...
    }

    
}