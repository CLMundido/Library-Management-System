<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    protected $fillable = [
        'borrow_record_id',
        'amount',
        'days_late',
        'reason',
        'paid',
    ];

    public function borrowRecord()
    {
        return $this->belongsTo(BorrowRecord::class);
    }
}
