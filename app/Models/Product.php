<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name', 'sku', 'description', 'price', 'discount_price',
        'stock_qty', 'type', 'brand_id', 'category_id',
        'is_active', 'created_at', 'updated_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'stock_qty' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Whether the product currently has a valid sale price.
     * A discount is only valid when it is set, greater than zero,
     * and strictly lower than the regular price.
     */
    public function hasDiscount(): bool
    {
        if ($this->discount_price === null) {
            return false;
        }

        $discount = (float) $this->discount_price;
        $price = (float) $this->price;

        return $discount > 0 && $discount < $price;
    }

    /**
     * The price the customer actually pays (sale price when valid).
     */
    public function effectivePrice(): float
    {
        if ($this->hasDiscount()) {
            return (float) $this->discount_price;
        }

        return (float) $this->price;
    }

    /**
     * The crossed-out original price, or null when there is no sale.
     */
    public function originalPrice(): ?float
    {
        if ($this->hasDiscount()) {
            return (float) $this->price;
        }

        return null;
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class);
    }

    /**
     * Everything that happened to this product (created, updated, stock changes + notes).
     */
    public function histories(): HasMany
    {
        return $this->hasMany(ProductHistory::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(ProductView::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function warranties(): HasMany
    {
        return $this->hasMany(Warranty::class);
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }
}
