<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarrantyHistory extends Model
{
    protected $table = 'warranty_history';

    protected $fillable = [
        'warranty_id', 'action_date', 'description', 'performed_by',
    ];

    public $timestamps = false;

    protected $casts = [
        'action_date' => 'datetime',
    ];

    public function warranty(): BelongsTo
    {
        return $this->belongsTo(Warranty::class);
    }
}
