<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VehicleNewMail extends Mailable
{
    use Queueable, SerializesModels;
    public $_subject;
    public $vehicle;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($_subject,$vehicle)
    {
        $this->_subject=$_subject;
        $this->vehicle=$vehicle;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.vehicle-new')->subject($this->_subject);
    }
}
