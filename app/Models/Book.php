<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'author_id',
        'genre_id',
        'price',
        'stock',
    ];

    /**
     * Get the author associated with the book.
     */
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * Get the genre associated with the book.
     */
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
