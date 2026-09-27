<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductHistory extends Model
{
    protected $table = 'product_histories';

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'user_id',
        'action',
        'old_stock_qty',
        'new_stock_qty',
        'details',
        'note',
        'created_at',
    ];

    protected $casts = [
        'old_stock_qty' => 'integer',
        'new_stock_qty' => 'integer',
        'created_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
