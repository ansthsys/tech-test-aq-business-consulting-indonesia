<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TodoList extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "title",
        "completed",
        "order",
    ];

    public function markComplete()
    {
        $this->update(['completed' => true]);
    }

    public function markUncomplete()
    {
        $this->update(['completed' => false]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
