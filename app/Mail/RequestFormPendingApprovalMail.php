<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestFormPendingApprovalMail extends Mailable
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
    public function __construct($user,$_message,$_subject)
    {
        $this->userName=$user->firstName." ".$user->lastName;
        $this->_subject=$_subject;
        $this->_message=$_message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.request-form-pending-approval')->subject($this->_subject);
    }
}
