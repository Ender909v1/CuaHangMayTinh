<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'bill_code', 'user_id', 'order_date', 'total_amount', 'status',
        'payment_method', 'payment_status', 'transaction_id',
        'email_sent', 'email_sent_at', 'user_seen_at', 'shipping_address',
    ];

    public $timestamps = false;

    protected $casts = [
        'order_date' => 'datetime',
        'updated_at' => 'datetime',
        'user_seen_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'email_sent' => 'boolean',
        'email_sent_at' => 'datetime',
    ];

    public const STATUSES = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

    public const PAYMENT_METHODS = ['cod', 'bank_transfer', 'credit_card', 'e_wallet'];

    public const PAYMENT_STATUSES = ['pending', 'paid', 'failed', 'refunded'];

    /**
     * Bank/QR transfer money is in flight: method is bank_transfer and
     * payment has not reached a final state yet.
     */
    public function paymentNeedsAttention(): bool
    {
        return $this->payment_method === 'bank_transfer'
            && in_array($this->payment_status, ['pending', 'failed'], true);
    }

    /**
     * True = customer hasn't checked this order yet, or admin changed the
     * status after the customer's last view ("!" badge in purchase history).
     */
    public function needsAttention(): bool
    {
        if ($this->user_seen_at === null) {
            return true;
        }

        if ($this->updated_at === null) {
            return false;
        }

        return $this->updated_at->greaterThan($this->user_seen_at);
    }

    /**
     * Customer may still fix a mistaken order: only while it is pending
     * (not yet processed/shipped) and payment is not already captured.
     */
    public function canBeCancelledByCustomer(): bool
    {
        return $this->status === 'pending' && $this->payment_status !== 'paid';
    }

    /**
     * Same gate for removing a single item line from the order.
     */
    public function canRemoveItems(): bool
    {
        return $this->canBeCancelledByCustomer();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
