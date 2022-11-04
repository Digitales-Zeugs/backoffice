<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyMemberApproval extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;
    public $registration_id;

    public function __construct(string $nombre, int $registration_id)
    {
        $this->nombre = $nombre;
        $this->registration_id = $registration_id;
    }

    public function build()
    {
        return $this->from('socios@sadaic.org.ar')
                    ->subject('Notificación de Aprobación | SADAIC')
                    ->view('mails.notify-member-approval');
    }
}
