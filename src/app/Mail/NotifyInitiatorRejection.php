<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyInitiatorRejection extends Mailable
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
                    ->subject('Notificación de Solicitud de Registro | SADAIC')
                    ->view('mails.notify-initiator-rejection');
    }
}
