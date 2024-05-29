<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $orderMessage;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orderMessage)
    {
        $this->orderMessage = $orderMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from($this->orderMessage['site_email'], 'Liquor Store')
                    ->subject($this->orderMessage['subject'])
                    ->view('emails/mail_to_customer');


        // return $this->view('view.name');
    }
}
