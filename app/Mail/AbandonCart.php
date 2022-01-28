<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AbandonCart extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $abdncart;
    public function __construct($abdncart)
    {
        $this->abdncart=$abdncart;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from($this->abdncart['fromemail'],$this->abdncart['name'])
        ->subject($this->abdncart['subject'])
        ->markdown('emails.abandoncart.abandoncart');  
 
      

    }
}
