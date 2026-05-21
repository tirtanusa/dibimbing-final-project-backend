<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name'             => 'Potong Rambut',
                'description'      => 'Potong rambut standar sesuai keinginan pelanggan.',
                'price'            => 35000,
                'duration_minutes' => 30,
            ],
            [
                'name'             => 'Potong + Cuci',
                'description'      => 'Potong rambut dilanjutkan dengan keramas menggunakan shampoo premium.',
                'price'            => 50000,
                'duration_minutes' => 45,
            ],
            [
                'name'             => 'Cukur Jenggot',
                'description'      => 'Cukur dan rapikan jenggot menggunakan pisau cukur profesional.',
                'price'            => 25000,
                'duration_minutes' => 20,
            ],
            [
                'name'             => 'Potong + Cukur Jenggot',
                'description'      => 'Paket lengkap potong rambut dan cukur jenggot.',
                'price'            => 55000,
                'duration_minutes' => 60,
            ],
            [
                'name'             => 'Hair Coloring',
                'description'      => 'Pewarnaan rambut dengan berbagai pilihan warna.',
                'price'            => 150000,
                'duration_minutes' => 90,
            ],
            [
                'name'             => 'Creambath',
                'description'      => 'Perawatan rambut dengan cream khusus untuk menutrisi rambut.',
                'price'            => 75000,
                'duration_minutes' => 60,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}