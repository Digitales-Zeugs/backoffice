<?php

namespace App\Mail;

use App\Models\Work\Distribution;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyDistribution extends Mailable
{
    use Queueable, SerializesModels;

    public $distribution;

    public function __construct(Distribution $distribution)
    {
        $this->distribution = $distribution;
    }

    public function build()
    {
        return $this->from('socios@sadaic.org.ar')
                    ->view('mails.notify-distribution');
    }
}
