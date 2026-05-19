<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Models\TimeSlot;
use App\Models\BarberSchedule;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TimeSlotController extends Controller
{
    use ApiResponse;
    public function index(Request $request){
        $slots = TimeSlot::where('barber_id', $request->barber_id)
            ->where('date', $request->date)
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->paginate($request->get('limit', 10));
            
        return $this->successResponse($slots, 'Data time slot berhasil diambil');
    }

    public function block(string $id){
        $slot = TimeSlot::findOrFail($id);
        if($slot->status == 'blocked' || $slot->status == 'booked'){
            return $this->errorResponse('Slot waktu sudah di-block atau di-booking', 400);
        }
        $slot->update(['status' => 'blocked']);
        return $this->successResponse($slot, 'Slot waktu berhasil di-block');
    }

    public function unblock(string $id){
        $slot = TimeSlot::findOrFail($id);
        if($slot->status == 'available' || $slot->status == 'booked'){
            return $this->errorResponse('Slot waktu sedang Available atau di-booking', 400);
        }
        $slot->update(['status' => 'available']);
        return $this->successResponse($slot, 'Slot waktu berhasil di-unblock');
    }

    public function generate(Request $request, string $barberId, string $date)
    {
        $dayOfWeek = date('w', strtotime($date));

        $schedule = BarberSchedule::where('barber_id', $barberId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if (!$schedule) {
            return $this->errorResponse(null, 'Barber tidak memiliki jadwal di hari ini', 404);
        }

        $minDuration = Service::min('duration_minutes');
        if (!$minDuration) {
            return $this->errorResponse(null, 'Tidak ada service yang tersedia', 404);
        }

        DB::transaction(function () use ($barberId, $date, $schedule, $minDuration) {
            $current = Carbon::createFromFormat('H:i:s', $schedule->start_time);
            $end = Carbon::createFromFormat('H:i:s', $schedule->end_time);

            while ($current->copy()->addMinutes($minDuration)->lte($end)) {
                TimeSlot::firstOrCreate(
                    [
                        'barber_id' => $barberId,
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

        return $this->successResponse(null, 'Slot waktu berhasil di-generate');
    }
}
