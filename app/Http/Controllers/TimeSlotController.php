<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Models\TimeSlot;
use App\Models\BarberSchedule;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class TimeSlotController extends Controller
{
    use ApiResponse;
    public function index(){
        $slots = TimeSlot::all();
        return $this->successResponse($slots, 'Data time slot berhasil diambil');
    }

    public function block(string $id){
        $slot = TimeSlot::findOrFail($id);
        $slot->update(['status' => 'blocked']);
        return $this->successResponse($slot, 'Slot waktu berhasil di-block');
    }

    public function unblock(string $id){
        $slot = TimeSlot::findOrFail($id);
        $slot->update(['status' => 'available']);
        return $this->successResponse($slot, 'Slot waktu berhasil di-unblock');
    }

    public function generate(string $date, string $barberId){
        $generate = DB::transaction(function () use ($date, $barberId) {
            $dayOfWeek = date('w', strtotime($date));
            // Ambil jadwal keja barber di hari tertentu
            $schedule = BarberSchedule::where('barber_id', $barberId)->where('day_of_week', $dayOfWeek)->first();
            
            if($schedule == null){
                return $this->errorResponse('Barber tidak memiliki jadwal di hari ini', 404);
            }
            // Ambil durasi service terpendek
            $minDuration = Service::min('duration');
            if($minDuration == null){
                return $this->errorResponse('Barber tidak memiliki service', 404);
            }
            // Loop jadwal kerja berdasarkan start_time -> end_time
            // Insert ke time_slot dengan status available

            // Notes : skip jika slot untuk barber + tanggal tersebut sudah ada di database
            $startTime = $schedule->start_time;
            $endTime = $schedule->end_time;
            while ($startTime < $endTime) {
                TimeSlot::create([
                    'date' => $date,
                    'barber_id' => $barberId,
                    'start_time' => $startTime,
                    'end_time' => $startTime + $duration,
                    'status' => 'available'
                ]);
                $startTime += $duration;
            }
        });

    }
}
