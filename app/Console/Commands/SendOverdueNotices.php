<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BorrowRecord;
use App\Mail\OverdueNoticeMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendOverdueNotices extends Command
{
    protected $signature = 'library:send-overdue-notices';
    protected $description = 'Send email notifications to users with overdue borrowed books';

    public function handle()
    {
        $overdueRecords = BorrowRecord::with(['user', 'book'])
            ->where('status', '!=', 'Returned')
            ->whereDate('due_date', '<', Carbon::now())
            ->get();

        foreach ($overdueRecords as $record) {
            if ($record->user && $record->user->email) {
                Mail::to($record->user->email)->send(new OverdueNoticeMail($record));
                $this->info("Overdue email sent to {$record->user->email}");
            }
        }

        return Command::SUCCESS;
    }
}
