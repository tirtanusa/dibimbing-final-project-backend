<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barber;

class BarberSeeder extends Seeder
{
    public function run(): void
    {
        $barbers = [
            [
                'name'      => 'Ahmad Fauzi',
                'bio'       => 'Barber berpengalaman 5 tahun, spesialis fade dan undercut.',
                'rating'    => 4.80,
                'is_active' => true,
            ],
            [
                'name'      => 'Rizky Ramadhan',
                'bio'       => 'Ahli dalam teknik pompadour dan quiff modern.',
                'rating'    => 4.50,
                'is_active' => true,
            ],
            [
                'name'      => 'Dimas Ardiansyah',
                'bio'       => 'Spesialis cukur jenggot dan perawatan rambut pria.',
                'rating'    => 4.20,
                'is_active' => true,
            ],
            [
                'name'      => 'Bagas Prasetyo',
                'bio'       => 'Barber muda dengan keahlian gaya rambut modern dan klasik.',
                'rating'    => 3.90,
                'is_active' => false,
            ],
        ];

        foreach ($barbers as $barber) {
            Barber::create($barber);
        }
    }
}