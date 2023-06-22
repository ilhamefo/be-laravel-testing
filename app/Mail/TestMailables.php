<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class TestMailables extends Mailable
{
    use Queueable, SerializesModels;

    public $path;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('view.name');
        return $this->from(env("MAIL_FROM_ADDRESS", "no-reply@mail.ilhamefo.me"), env("MAIL_FROM_NAME", "Laravel"))
            ->view("emails.orders.test")
            ->attachFromStorageDisk('s3', $this->path, 'summary.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
