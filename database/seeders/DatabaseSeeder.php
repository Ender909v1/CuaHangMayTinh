<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ProductImageSeeder::class,
            ProductSpecificationSeeder::class,
            ProductViewSeeder::class,
            ReviewSeeder::class,
            WishlistSeeder::class,
            CartItemSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            WarrantySeeder::class,
            WarrantyHistorySeeder::class,
            SupportTicketSeeder::class,
            TicketReplySeeder::class,
            AiConversationSeeder::class,
        ]);
    }
}
