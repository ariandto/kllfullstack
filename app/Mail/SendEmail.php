<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function build()
    {
        return $this->from('ikhsan.maulana@kawanlamacorp.com') // Ganti dengan email pengirim
            ->subject($this->details['title'])  // Menggunakan title dari $details
            ->view('emails.template'); // Menggunakan view email
    }
}
