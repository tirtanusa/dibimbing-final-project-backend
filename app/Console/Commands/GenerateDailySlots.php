<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Barber;
use App\Models\BarberSchedule;
use App\Models\Service;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

#[Signature('app:generate-daily-slots')]
#[Description('Generate daily time slots for barbers')]
class GenerateDailySlots extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow();
        $dayOfWeek = $tomorrow->dayOfWeek;
        $date = $tomorrow->toDateString();

        $barbers = Barber::where('is_active', true)->get();
        $minDuration = Service::min('duration_minutes');

        if (!$minDuration) {
            $this->error('Tidak ada service yang tersedia');
            return;
        }

        foreach ($barbers as $barber) {
            $schedule = BarberSchedule::where('barber_id', $barber->id)
                ->where('day_of_week', $dayOfWeek)
                ->where('is_active', true)
                ->first();

            if (!$schedule) {
                $this->info("Barber {$barber->name} tidak memiliki jadwal hari ini, skip.");
                continue;
            }

            DB::transaction(function () use ($barber, $date, $schedule, $minDuration) {
                $current = Carbon::createFromFormat('H:i:s', $schedule->start_time);
                $end = Carbon::createFromFormat('H:i:s', $schedule->end_time);

                while ($current->copy()->addMinutes($minDuration)->lte($end)) {
                    TimeSlot::firstOrCreate(
                        [
                            'barber_id' => $barber->id,
                            'date' => $date,
                            'start_time' => $current->format('H:i'),
                        ],
                        [
                            'end_time' => $current->copy()->addMinutes($minDuration)->format('H:i'),
                            'status' => 'available',
                        ]
                    );
                    $current->addMinutes($minDuration);
                }
            });

            $this->info("Slot berhasil di-generate untuk barber {$barber->name} tanggal {$date}");
        }

        $this->info('Semua slot berhasil di-generate');
    }
}
