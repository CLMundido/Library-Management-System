<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BorrowRecord;
use Filament\Notifications\Notification;

class Book extends Model
{
    protected $fillable = [
        'cover_image',
        'isbn',
        'title',
        'author',
        'description',
        'genre',
        'copies',
        'book_category_id',
    ];

    protected $guarded = ['availability'];

    public static function booted()
    {
        static::saving(function ($book) {
            $book->availability = $book->copies > 0 ? 'available' : 'out of stock';
        });
    }

    public function borrowRequests()
    {
        return $this->hasMany(BorrowRequest::class);
    }

    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }

    public function bookCategory()
    {
        return $this->belongsTo(BookCategory::class);
    }
}
