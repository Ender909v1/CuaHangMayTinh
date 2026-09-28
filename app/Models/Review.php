<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'rating', 'comment',
        'admin_response', 'admin_responded_at', 'created_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'rating' => 'integer',
        'admin_responded_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Whether an admin already answered this review.
     */
    public function hasAdminResponse(): bool
    {
        return filled($this->admin_response);
    }
}
