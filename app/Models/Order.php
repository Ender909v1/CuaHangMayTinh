<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_date', 'total_amount', 'status',
        'payment_method', 'payment_status', 'transaction_id',
        'email_sent', 'email_sent_at', 'shipping_address',
    ];

    public $timestamps = false;

    protected $casts = [
        'order_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'email_sent' => 'boolean',
        'email_sent_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
