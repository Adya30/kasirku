<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed 1 Admin account
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Kasirku',
                'password' => Hash::make('password123'),
            ]
        );

        // Seed Sample Products
        $products = [
            [
                'barcode' => '89686010023',
                'name' => 'Indomie Goreng Special',
                'category' => 'Makanan',
                'stock' => 120,
                'price' => 3500,
            ],
            [
                'barcode' => '8886008101053',
                'name' => 'Aqua Air Mineral 600ml',
                'category' => 'Minuman',
                'stock' => 200,
                'price' => 4000,
            ],
            [
                'barcode' => '8992761136056',
                'name' => 'Coca Cola 390ml',
                'category' => 'Minuman',
                'stock' => 75,
                'price' => 6000,
            ],
            [
                'barcode' => '8999999056250',
                'name' => 'Sabun Mandi Lifebuoy 85g',
                'category' => 'Kebutuhan Rumah',
                'stock' => 90,
                'price' => 4500,
            ],
            [
                'barcode' => '8999999052443',
                'name' => 'Pasta Gigi Pepsodent 120g',
                'category' => 'Kebutuhan Rumah',
                'stock' => 60,
                'price' => 12500,
            ],
            [
                'barcode' => '8992753300588',
                'name' => 'Roti Tawar Sari Roti Kupas',
                'category' => 'Makanan',
                'stock' => 25,
                'price' => 16000,
            ],
            [
                'barcode' => '8991389220054',
                'name' => 'Kopi Kapal Api Spesial Mix 10x25g',
                'category' => 'Minuman',
                'stock' => 50,
                'price' => 14500,
            ],
            [
                'barcode' => '8999999551106',
                'name' => 'Rinso Liquid Detergent 750ml',
                'category' => 'Kebutuhan Rumah',
                'stock' => 40,
                'price' => 22000,
            ]
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['barcode' => $product['barcode']],
                $product
            );
        }
    }
}
