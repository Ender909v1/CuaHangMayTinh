<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ProductImage extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_id', 'image_url', 'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function resolvedUrl(): string
    {
        if (! Str::contains($this->image_url, 'via.placeholder.com')) {
            return Str::startsWith($this->image_url, ['http://', 'https://'])
                ? $this->image_url
                : asset($this->image_url);
        }

        $name = Str::lower($this->product()->value('name') ?? '');

        if (Str::contains($name, ['laptop', 'zenbook', 'xps', 'stealth', 'pavilion', 'predator', 'legion'])) {
            $images = [
                'laptop_asus_rtx3060.jpg',
                'laptop-hp-omen-16-2025.jpg',
                'slim_laptop.jpg',
                'surface_laptop.jpg',
                'zephyrus_G16.jpg',
                'Macbook_M13_pro_14inch.jpg',
            ];

            return asset('tailstore4-main/laptop/'.$images[$this->product_id % count($images)]);
        }

        if (Str::contains($name, ['ryzen', 'intel', 'rtx', 'desktop', 'aurora', 'tuf', 'component'])) {
            $images = [
                'AMD_ryzen7_7800X3D.jpg',
                'intel-core-i7-13700k.jpg',
                'rtx-4070.jpg',
                'Corsair_Vengeance_32G_DDR5.jpg',
            ];

            return asset('tailstore4-main/pc_part/'.$images[$this->product_id % count($images)]);
        }

        return asset('tailstore4-main/logo/accessories.jpg');
    }
}
