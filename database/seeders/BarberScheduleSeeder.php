<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BarberSchedule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BarberScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // Ahmad Fauzi (barber_id: 1) — Senin sampai Sabtu
        $ahmedSchedule = [1, 2, 3, 4, 5, 6];
        foreach ($ahmedSchedule as $day) {
            BarberSchedule::create([
                'barber_id'   => 1,
                'day_of_week' => $day,
                'start_time'  => '09:00',
                'end_time'    => '17:00',
                'is_active'   => true,
            ]);
        }

        // Rizky Ramadhan (barber_id: 2) — Senin sampai Jumat
        $rizkySchedule = [1, 2, 3, 4, 5];
        foreach ($rizkySchedule as $day) {
            BarberSchedule::create([
                'barber_id'   => 2,
                'day_of_week' => $day,
                'start_time'  => '10:00',
                'end_time'    => '18:00',
                'is_active'   => true,
            ]);
        }

        // Dimas Ardiansyah (barber_id: 3) — Selasa sampai Minggu
        $dimasSchedule = [0, 2, 3, 4, 5, 6];
        foreach ($dimasSchedule as $day) {
            BarberSchedule::create([
                'barber_id'   => 3,
                'day_of_week' => $day,
                'start_time'  => '08:00',
                'end_time'    => '16:00',
                'is_active'   => true,
            ]);
        }

        // Bagas Prasetyo (barber_id: 4) — is_active false, jadwal tetap dibuat
        // tapi tidak akan di-generate slot karena barber tidak aktif
        $bagasSchedule = [1, 2, 3, 4, 5];
        foreach ($bagasSchedule as $day) {
            BarberSchedule::create([
                'barber_id'   => 4,
                'day_of_week' => $day,
                'start_time'  => '09:00',
                'end_time'    => '17:00',
                'is_active'   => true,
            ]);
        }
    }
}