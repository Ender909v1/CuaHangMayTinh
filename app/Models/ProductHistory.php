<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductHistory extends Model
{
<<<<<<< HEAD
    protected $table = 'product_histories';

    public $timestamps = false;
=======
    protected $table = 'product_history';
>>>>>>> 07e3ef880b20a27898947149096f7c1a02933c6f

    protected $fillable = [
        'product_id',
        'user_id',
        'action',
        'details',
        'created_at',
    ];

<<<<<<< HEAD
=======
    public $timestamps = false;

>>>>>>> 07e3ef880b20a27898947149096f7c1a02933c6f
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
<<<<<<< HEAD
        return $this->belongsTo(Product::class);
=======
        return $this->belongsTo(Product::class, 'product_id');
>>>>>>> 07e3ef880b20a27898947149096f7c1a02933c6f
    }

    public function user(): BelongsTo
    {
<<<<<<< HEAD
        return $this->belongsTo(User::class);
=======
        return $this->belongsTo(User::class, 'user_id');
>>>>>>> 07e3ef880b20a27898947149096f7c1a02933c6f
    }
}
