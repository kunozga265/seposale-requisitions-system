<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestFormWaitingInitiationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $userName;
    public $_message;
    public $_subject;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($accountant,$message,$subject)
    {
        $this->userName=$accountant->firstName." ".$accountant->lastName;
        $this->_subject=$subject;
        $this->_message=$message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.request-form-waiting-initiation')->subject($this->_subject);
    }
}
