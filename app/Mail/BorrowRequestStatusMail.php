<?php

namespace App\Mail;

use App\Models\BorrowRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BorrowRequestStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $borrowRequest;

    public function __construct(BorrowRequest $borrowRequest)
    {
        $this->borrowRequest = $borrowRequest;
    }

    public function build()
    {
        return $this->subject('Borrow Request ' . ucfirst($this->borrowRequest->status))
                    ->view('emails.borrow-request-status');
    }
}
