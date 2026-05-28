<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Traits\ApiResponse;
use App\Models\TimeSlot;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponse;
    public function index(Request $request)
    {
        $bookings = Booking::paginate($request->get('limit', 10));
        return $this->successResponse($bookings, 'Data booking berhasil diambil');
    }

    public function myBookings(Request $request)
    {
        $user = $request->user();
        $bookings = Booking::where('user_id', $user->id)->paginate($request->get('limit', 10));
        return $this->successResponse($bookings, 'Data booking berhasil diambil');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'user_id' => 'required|exists:users,id',
            'barber_id' => 'required|exists:barbers,id',
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string',
        ],[
            'user_id.required' => 'User ID wajib diisi',
            'user_id.exists' => 'User ID tidak ditemukan',
            'barber_id.required' => 'Barber ID wajib diisi',
            'barber_id.exists' => 'Barber ID tidak ditemukan',
            'service_id.required' => 'Service ID wajib diisi',
            'service_id.exists' => 'Service ID tidak ditemukan',
            'booking_date.required' => 'Tanggal booking wajib diisi',
            'booking_date.date' => 'Tanggal booking harus berupa format tanggal',
            'start_time.required' => 'Jam mulai wajib diisi',
            'start_time.date_format' => 'Jam mulai harus berupa format H:i',
            'end_time.required' => 'Jam selesai wajib diisi',
            'end_time.date_format' => 'Jam selesai harus berupa format H:i',
            'end_time.after' => 'Jam selesai harus setelah jam mulai',
            'notes.string' => 'Notes harus berupa string',
        ]);

        $conflict = Booking::where('barber_id', $validate['barber_id'])
            ->where('booking_date', $validate['booking_date'])
            ->whereNotIn('status', ['cancelled'])
            ->where(function($query) use ($validate) {
                $query->where('start_time', '<', $validate['end_time'])
                    ->where('end_time', '>', $validate['start_time']);
            })
            ->exists();

        if ($conflict) {
            return $this->errorResponse( 'Slot waktu tidak tersedia, barber sudah memiliki booking di waktu ini', 409);
        }

        $booking = DB::transaction(function() use ($validate) {
            $booking = Booking::create($validate + ['status' => 'pending']);
            $service = Service::min('duration_minutes');
            if (!$service) {
                return $this->errorResponse(null, 'Tidak ada service yang tersedia', 404);
            }
            
            for($time = $validate['start_time']; $time < $validate['end_time']; $time = date('H:i', strtotime($time . ' +' . $service . ' minutes'))) {
                TimeSlot::where('barber_id', $validate['barber_id'])
                    ->where('date', $validate['booking_date'])
                    ->where('start_time', $time)
                    ->update([
                        'booking_id' => $booking->id,
                    'status' => 'booked'
                ]);
            }

            return $booking;
        });
        
        return $this->createdResponse($booking, 'Data booking berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $booking = Booking::findOrFail($id);
        return $this->successResponse($booking, 'Data booking berhasil diambil');
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);
        
        $validate = $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
        ],[
            'status.required' => 'Status wajib diisi',
            'status.in' => 'Status tidak valid',
        ]);
        
        $booking->update($validate);
        return $this->successResponse($booking, 'Status booking berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function cancel(string $id)
    {
        $booking = Booking::findOrFail($id);

        if (in_array($booking->status, ['cancelled', 'completed'])) {
            return $this->errorResponse('Booking tidak dapat dibatalkan', 422);
        }

        $booking->update(['status' => 'cancelled']);

        TimeSlot::where('booking_id', $booking->id)
            ->update([
                'booking_id' => null,
                'status' => 'available'
            ]);

        return $this->successResponse($booking, 'Data booking berhasil dibatalkan');
    }
}
