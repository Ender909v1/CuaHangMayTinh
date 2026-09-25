<?php

use App\Models\Product;
use App\Models\ProductHistory;

$checks = [
    'dashboard: history latest 8' => fn () => ProductHistory::with(['user', 'product'])->latest()->take(8)->get(),
    'dashboard: product counts' => fn () => [Product::count(), Product::where('is_active', true)->count(), Product::where('stock_qty', '<=', 5)->count()],
    'products: paginate 12' => fn () => Product::with(['brand', 'category'])->latest()->paginate(12),
    'history: paginate 20' => fn () => ProductHistory::with(['user', 'product'])->latest()->paginate(20),
    'detail: images + specifications' => function () {
        $product = Product::first();
        if (! $product) {
            return 'no products to load';
        }

        return $product->load(['brand', 'category', 'images', 'specifications'])->name;
    },
    'write: ProductHistory::create' => function () {
        $entry = ProductHistory::create([
            'product_id' => null,
            'user_id' => null,
            'action' => 'migration_check',
            'details' => 'Table availability check',
        ]);
        $id = $entry->id;
        $entry->delete();

        return 'inserted & removed id '.$id;
    },
];

foreach ($checks as $label => $callback) {
    try {
        $result = $callback();
        $summary = is_object($result) ? get_class($result).' (count '.count($result).')' : (is_array($result) ? implode('/', $result) : $result);
        echo 'OK   '.str_pad($label, 34).' -> '.$summary.PHP_EOL;
    } catch (\Throwable $e) {
        echo 'FAIL '.str_pad($label, 34).' -> '.get_class($e).': '.$e->getMessage().PHP_EOL;
    }
}
