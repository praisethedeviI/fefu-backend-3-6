<?php

namespace App\Mail;

use App\Models\Appeal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppealCreated extends Mailable
{
    use Queueable, SerializesModels;

    public Appeal $appeal;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Appeal $appeal)
    {
        $this->appeal = $appeal;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.appeal_created');
    }
}
