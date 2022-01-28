<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Distributors extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $dist;
    public function __construct($distributor)
    {
        $this->dist=$distributor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         return $this->from($this->dist['fromemail'],$this->dist['name'])
        ->subject($this->dist['subject'])
        ->markdown('emails.distributors.distributor');
    }
}
