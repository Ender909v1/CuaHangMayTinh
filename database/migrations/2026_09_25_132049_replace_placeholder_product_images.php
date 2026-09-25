<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $images = DB::table('product_images')
            ->join('products', 'products.id', '=', 'product_images.product_id')
            ->where('product_images.image_url', 'like', '%via.placeholder.com%')
            ->get(['product_images.id', 'product_images.product_id', 'products.name']);

        foreach ($images as $image) {
            $name = Str::lower($image->name);
            $folder = Str::contains($name, ['laptop', 'zenbook', 'xps', 'stealth', 'pavilion', 'predator', 'legion'])
                ? 'laptop'
                : (Str::contains($name, ['ryzen', 'intel', 'rtx', 'desktop', 'aurora', 'tuf', 'component'])
                    ? 'pc_part'
                    : 'logo');
            $files = match ($folder) {
                'laptop' => [
                    'laptop_asus_rtx3060.jpg',
                    'laptop-hp-omen-16-2025.jpg',
                    'slim_laptop.jpg',
                    'surface_laptop.jpg',
                    'zephyrus_G16.jpg',
                    'Macbook_M13_pro_14inch.jpg',
                ],
                'pc_part' => [
                    'AMD_ryzen7_7800X3D.jpg',
                    'intel-core-i7-13700k.jpg',
                    'rtx-4070.jpg',
                    'Corsair_Vengeance_32G_DDR5.jpg',
                ],
                default => ['accessories.jpg'],
            };

            DB::table('product_images')
                ->where('id', $image->id)
                ->update([
                    'image_url' => 'tailstore4-main/'.$folder.'/'.$files[$image->product_id % count($files)],
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Original placeholder URLs cannot be restored after replacement.
    }
};
