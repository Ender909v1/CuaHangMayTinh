<?php

echo 'Connection: '.config('database.default').PHP_EOL;
echo 'Database  : '.DB::connection()->getDatabaseName().PHP_EOL.PHP_EOL;

$tables = array_map(fn ($t) => array_values((array) $t)[0], DB::select('SHOW TABLES'));
sort($tables);
echo 'Tables ('.count($tables).'):'.PHP_EOL;
foreach ($tables as $table) {
    echo '  - '.$table.PHP_EOL;
}

echo PHP_EOL.'Required check:'.PHP_EOL;
foreach (['users', 'products', 'brands', 'categories', 'product_histories', 'product_images', 'product_specifications', 'orders', 'order_items', 'cart_items', 'wishlists', 'reviews', 'product_views', 'warranties', 'warranty_history', 'support_tickets', 'ticket_replies', 'ai_conversations'] as $table) {
    echo str_pad($table, 26).(Schema::hasTable($table) ? 'OK' : 'MISSING').PHP_EOL;
}
