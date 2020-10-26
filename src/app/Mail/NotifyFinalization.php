<?php

namespace App\Mail;

use App\Models\Work\Distribution;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyFinalization extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;

    public function __construct(Distribution $registration)
    {
        $this->registration = $registration;
    }

    public function build()
    {
        return $this->from('socios@sadaic.org.ar')
                    ->view('mails.notify-finalization');
    }
}
