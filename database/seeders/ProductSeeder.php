<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $brandIds = DB::table('brands')->pluck('id')->all();
        $categoryIds = DB::table('categories')->pluck('id')->all();

        $products = [
            ['ASUS ZenBook 14 OLED', 'LAP-001', 'Laptop ultrabook với màn hình OLED 14 inch, CPU Intel Core i7, RAM 16GB và SSD 1TB.', 28990000, 25990000, 18, 'laptop', 'Laptop', 1],
            ['Dell XPS 13 Plus', 'LAP-002', 'Laptop cao cấp cho công việc văn phòng và sáng tạo với thiết kế rất mỏng.', 32990000, 29990000, 12, 'laptop', 'Business Laptop', 2],
            ['MSI Stealth 15', 'LAP-003', 'Laptop gaming mạnh mẽ với GPU RTX 4060, màn hình 15.6 inch 144Hz.', 34990000, 31990000, 8, 'laptop', 'Gaming Laptop', 6],
            ['HP Pavilion 15', 'LAP-004', 'Laptop đa năng cho học tập, làm việc và giải trí hàng ngày.', 21990000, 19990000, 15, 'laptop', 'Business Laptop', 3],
            ['Acer Predator Helios 18', 'LAP-005', 'Laptop gaming màn hình 18 inch, hiệu năng cao cho người chơi chuyên nghiệp.', 47990000, 42990000, 5, 'laptop', 'Gaming Laptop', 5],
            ['Lenovo Legion 5', 'LAP-006', 'Laptop gaming bền bỉ, tản nhiệt tốt và hiệu suất ổn định khi chơi game.', 30990000, 27990000, 6, 'laptop', 'Gaming Laptop', 4],
            ['Dell Alienware Aurora R16', 'PC-001', 'Desktop gaming mạnh với CPU Intel Core i7 và GPU RTX 4070.', 43990000, 40990000, 7, 'desktop', 'Gaming PC', 2],
            ['ASUS TUF Gaming Desktop', 'PC-002', 'Máy tính chơi game phong cách tản nhiệt mạnh và tối ưu hiệu suất.', 36990000, 33990000, 9, 'desktop', 'Gaming PC', 1],
            ['Samsung Odyssey G5', 'MON-001', 'Màn hình gaming 27 inch, độ phân giải 2K và tần số quét 144Hz.', 8990000, 8390000, 20, 'monitor', 'Gaming Monitor', 8],
            ['LG UltraGear 27GN950', 'MON-002', 'Màn hình hiển thị sắc nét cho game và thiết kế đồ họa.', 12990000, 11990000, 12, 'monitor', '4K Monitor', 8],
            ['Logitech G502 X Plus', 'PER-001', 'Chuột gaming cao cấp với cảm biến chính xác và thiết kế ergonomic.', 2490000, 2190000, 30, 'peripheral', 'Mouse', 9],
            ['Corsair K70 Pro', 'PER-002', 'Bàn phím cơ gaming với switch đỏ và đèn RGB nổi bật.', 3490000, 2990000, 25, 'peripheral', 'Keyboard', 10],
            ['Kingston NV2 2TB SSD', 'STR-001', 'Ổ SSD NVMe 2TB dung lượng lớn, đáp ứng nhu cầu lưu trữ và khởi động nhanh.', 3190000, 2890000, 40, 'storage', 'SSD', 11],
            ['Intel Core i7-14700K', 'CMP-001', 'Vi xử lý mạnh cho máy tính chơi game và làm việc chuyên nghiệp.', 11990000, 10990000, 17, 'component', 'CPU', 12],
            ['AMD Ryzen 7 7800X3D', 'CMP-002', 'CPU gaming hiệu năng cao với cache lớn cho trải nghiệm mượt mà.', 12990000, 11990000, 16, 'component', 'CPU', 13],
            ['Gigabyte RTX 4070', 'CMP-003', 'Card đồ họa mạnh cho trải nghiệm chơi game 2K và render hiệu ứng.', 18990000, 16990000, 10, 'component', 'GPU', 14],
            ['Logitech MX Master 3S', 'PER-003', 'Chuột làm việc chuyên nghiệp với độ chính xác cao và pin lâu.', 2990000, 2690000, 22, 'peripheral', 'Mouse', 9],
            ['Corsair Void Elite', 'PER-004', 'Tai nghe gaming với âm thanh rõ nét và đệm tai thoải mái.', 2790000, 2490000, 18, 'peripheral', 'Headset', 10],
        ];

        foreach ($products as $product) {
            [$name, $sku, $description, $price, $discountPrice, $stockQty, $type, $categoryName, $brandIndex] = $product;

            $categoryId = DB::table('categories')
                ->where('name', $categoryName)
                ->value('id');

            $brandId = $brandIds[$brandIndex - 1] ?? $brandIds[0];

            DB::table('products')->insert([
                'name' => $name,
                'sku' => $sku,
                'description' => $description,
                'price' => $price,
                'discount_price' => $discountPrice,
                'stock_qty' => $stockQty,
                'type' => $type,
                'brand_id' => $brandId,
                'category_id' => $categoryId ?? $categoryIds[0],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
