<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== ADMIN USER =====
        $admin = User::create([
            'name' => 'Admin FashionStore',
            'email' => 'admin@fashionstore.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081111111111',
            'address' => 'Jl. Admin No. 1, Jakarta',
        ]);

        // ===== CUSTOMER USERS =====
        $customers = collect([
            User::create(['name' => 'Rina Wijaya', 'email' => 'rina@email.com', 'password' => Hash::make('password'), 'role' => 'customer', 'phone' => '081234567890', 'address' => 'Jl. Sudirman No. 15, Jakarta']),
            User::create(['name' => 'Ahmad Fauzi', 'email' => 'ahmad@email.com', 'password' => Hash::make('password'), 'role' => 'customer', 'phone' => '082345678901', 'address' => 'Jl. Gatot Subroto No. 8, Bandung']),
            User::create(['name' => 'Siti Nurhaliza', 'email' => 'siti@email.com', 'password' => Hash::make('password'), 'role' => 'customer', 'phone' => '083456789012', 'address' => 'Jl. Diponegoro No. 22, Surabaya']),
            User::create(['name' => 'Budi Santoso', 'email' => 'budi@email.com', 'password' => Hash::make('password'), 'role' => 'customer', 'phone' => '084567890123', 'address' => 'Jl. Ahmad Yani No. 5, Semarang']),
            User::create(['name' => 'Dewi Lestari', 'email' => 'dewi@email.com', 'password' => Hash::make('password'), 'role' => 'customer', 'phone' => '085678901234', 'address' => 'Jl. Malioboro No. 30, Yogyakarta']),
            User::create(['name' => 'Raka Pratama', 'email' => 'raka@email.com', 'password' => Hash::make('password'), 'role' => 'customer', 'phone' => '086789012345', 'address' => 'Jl. Merdeka No. 12, Medan']),
        ]);

        // ===== CATEGORIES =====
        $categories = collect([
            Category::create(['name' => 'Baju Wanita', 'description' => 'Koleksi baju wanita terbaru', 'image' => 'https://picsum.photos/seed/fashion-women/400/300', 'is_active' => true]),
            Category::create(['name' => 'Baju Pria', 'description' => 'Koleksi baju pria modern', 'image' => 'https://picsum.photos/seed/fashion-men/400/300', 'is_active' => true]),
            Category::create(['name' => 'Dress', 'description' => 'Dress elegan untuk segala acara', 'image' => 'https://picsum.photos/seed/fashion-dress/400/300', 'is_active' => true]),
            Category::create(['name' => 'Jaket & Outer', 'description' => 'Jaket dan outerwear trendy', 'image' => 'https://picsum.photos/seed/fashion-jacket/400/300', 'is_active' => true]),
            Category::create(['name' => 'Aksesoris', 'description' => 'Aksesoris fashion pelengkap', 'image' => 'https://picsum.photos/seed/fashion-accessories/400/300', 'is_active' => true]),
        ]);

        // ===== PRODUCTS =====
        $products = collect([
            Product::create(['category_id' => $categories[0]->id, 'name' => 'Blouse Floral Premium', 'sku' => 'BW-001', 'price' => 285000, 'sale_price' => 228000, 'stock' => 25, 'size' => 'S/M/L', 'color' => 'Floral Pink', 'material' => 'Katun Premium', 'image' => 'https://picsum.photos/seed/blouse-floral/400/500', 'is_active' => true, 'is_featured' => true]),
            Product::create(['category_id' => $categories[0]->id, 'name' => 'Kemeja Wanita Casual', 'sku' => 'BW-002', 'price' => 195000, 'stock' => 40, 'size' => 'S/M/L/XL', 'color' => 'Putih', 'material' => 'Katun', 'image' => 'https://picsum.photos/seed/kemeja-wanita/400/500', 'is_active' => true]),
            Product::create(['category_id' => $categories[0]->id, 'name' => 'Rok Plisket Premium', 'sku' => 'BW-003', 'price' => 175000, 'stock' => 4, 'size' => 'S/M/L', 'color' => 'Navy', 'material' => 'Polyester', 'image' => 'https://picsum.photos/seed/rok-plisket/400/500', 'is_active' => true]),
            Product::create(['category_id' => $categories[0]->id, 'name' => 'Atasan Crop Knit', 'sku' => 'BW-004', 'price' => 155000, 'stock' => 30, 'size' => 'M/L', 'color' => 'Coklat', 'material' => 'Knit', 'image' => 'https://picsum.photos/seed/crop-knit/400/500', 'is_active' => true]),
            Product::create(['category_id' => $categories[1]->id, 'name' => 'Kemeja Batik Pria', 'sku' => 'BP-001', 'price' => 350000, 'stock' => 5, 'size' => 'M/L/XL', 'color' => 'Coklat Batik', 'material' => 'Katun Sutra', 'image' => 'https://picsum.photos/seed/batik-pria/400/500', 'is_active' => true, 'is_featured' => true]),
            Product::create(['category_id' => $categories[1]->id, 'name' => 'Kaos Polos Premium', 'sku' => 'BP-002', 'price' => 125000, 'stock' => 80, 'size' => 'S/M/L/XL', 'color' => 'Hitam', 'material' => 'Cotton Combed 30s', 'image' => 'https://picsum.photos/seed/kaos-polos/400/500', 'is_active' => true]),
            Product::create(['category_id' => $categories[1]->id, 'name' => 'Kemeja Lengan Pendek', 'sku' => 'BP-003', 'price' => 225000, 'stock' => 35, 'size' => 'M/L/XL', 'color' => 'Biru Muda', 'material' => 'Katun Linen', 'image' => 'https://picsum.photos/seed/kemeja-pendek/400/500', 'is_active' => true]),
            Product::create(['category_id' => $categories[2]->id, 'name' => 'Dress Casual Wanita', 'sku' => 'DR-001', 'price' => 425000, 'stock' => 2, 'size' => 'S/M', 'color' => 'Merah Maroon', 'material' => 'Chiffon', 'image' => 'https://picsum.photos/seed/dress-casual/400/500', 'is_active' => true, 'is_featured' => true]),
            Product::create(['category_id' => $categories[2]->id, 'name' => 'Maxi Dress Elegant', 'sku' => 'DR-002', 'price' => 520000, 'sale_price' => 450000, 'stock' => 15, 'size' => 'S/M/L', 'color' => 'Hijau Sage', 'material' => 'Satin', 'image' => 'https://picsum.photos/seed/maxi-dress/400/500', 'is_active' => true]),
            Product::create(['category_id' => $categories[3]->id, 'name' => 'Jaket Denim Classic', 'sku' => 'JO-001', 'price' => 520000, 'stock' => 18, 'size' => 'M/L/XL', 'color' => 'Biru Denim', 'material' => 'Denim', 'image' => 'https://picsum.photos/seed/jaket-denim/400/500', 'is_active' => true, 'is_featured' => true]),
            Product::create(['category_id' => $categories[3]->id, 'name' => 'Cardigan Rajut', 'sku' => 'JO-002', 'price' => 275000, 'stock' => 22, 'size' => 'M/L', 'color' => 'Krem', 'material' => 'Rajut', 'image' => 'https://picsum.photos/seed/cardigan-rajut/400/500', 'is_active' => true]),
            Product::create(['category_id' => $categories[4]->id, 'name' => 'Scarf Sutra Premium', 'sku' => 'AK-001', 'price' => 165000, 'stock' => 50, 'color' => 'Motif Abstrak', 'material' => 'Sutra', 'image' => 'https://picsum.photos/seed/scarf-sutra/400/500', 'is_active' => true]),
            Product::create(['category_id' => $categories[4]->id, 'name' => 'Topi Bucket Hat', 'sku' => 'AK-002', 'price' => 95000, 'stock' => 60, 'color' => 'Khaki', 'material' => 'Katun', 'image' => 'https://picsum.photos/seed/bucket-hat/400/500', 'is_active' => true]),
        ]);

        // ===== ORDERS =====
        $ordersData = [
            ['user_id' => $customers[0]->id, 'items' => [['product_id' => $products[0]->id, 'quantity' => 2]], 'status' => 'completed', 'payment_status' => 'paid'],
            ['user_id' => $customers[1]->id, 'items' => [['product_id' => $products[4]->id, 'quantity' => 1], ['product_id' => $products[5]->id, 'quantity' => 2]], 'status' => 'processing', 'payment_status' => 'paid'],
            ['user_id' => $customers[2]->id, 'items' => [['product_id' => $products[7]->id, 'quantity' => 1]], 'status' => 'shipped', 'payment_status' => 'paid'],
            ['user_id' => $customers[3]->id, 'items' => [['product_id' => $products[9]->id, 'quantity' => 1]], 'status' => 'completed', 'payment_status' => 'paid'],
            ['user_id' => $customers[4]->id, 'items' => [['product_id' => $products[2]->id, 'quantity' => 3]], 'status' => 'pending', 'payment_status' => 'unpaid'],
            ['user_id' => $customers[5]->id, 'items' => [['product_id' => $products[8]->id, 'quantity' => 1], ['product_id' => $products[11]->id, 'quantity' => 2]], 'status' => 'completed', 'payment_status' => 'paid'],
            ['user_id' => $customers[0]->id, 'items' => [['product_id' => $products[3]->id, 'quantity' => 1]], 'status' => 'completed', 'payment_status' => 'paid'],
            ['user_id' => $customers[2]->id, 'items' => [['product_id' => $products[6]->id, 'quantity' => 2]], 'status' => 'completed', 'payment_status' => 'paid'],
        ];

        foreach ($ordersData as $i => $orderData) {
            $items = $orderData['items'];
            $userId = $orderData['user_id'];
            unset($orderData['items']);

            $subtotal = 0;
            $orderItems = [];

            foreach ($items as $item) {
                $product = $products->firstWhere('id', $item['product_id']);
                $itemSubtotal = $product->effective_price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->effective_price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemSubtotal,
                    'size' => $product->size,
                    'color' => $product->color,
                ];
            }

            $shippingCost = 15000;
            $total = $subtotal + $shippingCost;
            $user = $customers->firstWhere('id', $userId);

            $order = Order::create([
                'order_number' => 'ORD-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'user_id' => $userId,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'discount' => 0,
                'total' => $total,
                'status' => $orderData['status'],
                'payment_method' => $i % 3 === 0 ? 'transfer' : ($i % 3 === 1 ? 'e-wallet' : 'cod'),
                'payment_status' => $orderData['payment_status'],
                'shipping_address' => $user->address,
                'created_at' => now()->subDays(rand(0, 14)),
            ]);

            foreach ($orderItems as $item) {
                $item['order_id'] = $order->id;
                OrderItem::create($item);
            }
        }
    }
}
