<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyRequestRejection extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;

    public function __construct(string $nombre)
    {
        $this->nombre = $nombre;
    }

    public function build()
    {
        return $this->from('socios@sadaic.org.ar')
                    ->view('mails.notify-request-rejection');
    }
}
