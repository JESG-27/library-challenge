<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'author',
        'publication_date',
        'is_available',
        'user_id',
    ];

    protected $casts = [
        'publication_date' => 'date',
        'is_available' => 'boolean',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'book_category');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isAvailable(): bool
    {
        return $this->is_available;
    }

    public function waitingList()
    {
        // Agregar un limitador en la consulta
        return $this->belongsToMany(User::class, 'book_user_waiting_list')
            ->withTimestamps()
            ->orderBy('book_user_waiting_list.created_at', 'asc');
    }
}
