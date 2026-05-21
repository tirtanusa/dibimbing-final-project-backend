<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name'        => 'Pomade Gatsby',
                'description' => 'Pomade water-based dengan hold kuat dan shine tinggi.',
                'price'       => 45000,
                'stock'       => 50,
                'category'    => 'pomade',
                'status'      => 'available',
            ],
            [
                'name'        => 'Pomade Suavecito',
                'description' => 'Pomade premium dengan aroma segar dan hold medium.',
                'price'       => 120000,
                'stock'       => 20,
                'category'    => 'pomade',
                'status'      => 'available',
            ],
            [
                'name'        => 'Shampoo Men Dove',
                'description' => 'Shampoo khusus pria untuk rambut sehat dan bersih.',
                'price'       => 25000,
                'stock'       => 40,
                'category'    => 'shampo',
                'status'      => 'available',
            ],
            [
                'name'        => 'Conditioner Pantene',
                'description' => 'Kondisioner untuk melembutkan dan menutrisi rambut.',
                'price'       => 30000,
                'stock'       => 8,
                'category'    => 'conditioner',
                'status'      => 'available',
            ],
            [
                'name'        => 'Hair Wax Brylcreem',
                'description' => 'Wax rambut untuk styling sehari-hari dengan hold ringan.',
                'price'       => 35000,
                'stock'       => 0,
                'category'    => 'pomade',
                'status'      => 'out_of_stock',
            ],
            [
                'name'        => 'Beard Oil',
                'description' => 'Minyak perawatan jenggot agar tetap lembut dan rapi.',
                'price'       => 65000,
                'stock'       => 15,
                'category'    => 'other',
                'status'      => 'available',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}