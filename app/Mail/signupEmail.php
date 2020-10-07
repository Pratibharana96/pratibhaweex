<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class signupEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->email_data= $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
  

        return $this->from('pratibharana596@gmail.com')
                    ->subject('mail pratibha Equiry')
                    ->view('mail.signupmail',['email_data'=>$this->email_data]);
    }
}
