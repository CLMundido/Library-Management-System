<?php

namespace App\Mail;

use App\Models\BorrowRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OverdueNoticeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $record;

    public function __construct(BorrowRecord $record)
    {
        $this->record = $record;
    }

    public function build()
    {
        return $this->subject('Overdue Book Notice')
                    ->view('emails.overdue-notice');
    }
}
