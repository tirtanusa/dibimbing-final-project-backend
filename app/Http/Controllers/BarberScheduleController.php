<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarberSchedule;
use App\Traits\ApiResponse;

class BarberScheduleController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $id)
    {
        $schedules = BarberSchedule::paginate($request->get('limit', 10));
        if($schedules->isEmpty()){
            return $this->notFoundResponse('Data jadwal barber untuk barber ' . $id . ' tidak ditemukan');
        }
        return $this->successResponse($schedules, 'Data jadwal barber berhasil diambil');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'barber_id' => 'required|exists:barbers,id',
            'day_of_week' => 'required|integer|min:0|max:6|unique:barber_schedules,day_of_week,NULL,id,barber_id,' . $request->barber_id,
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_active' => 'nullable|boolean'
        ],[
            'barber_id.required' => 'Barber ID wajib diisi',
            'barber_id.exists' => 'Barber ID tidak ditemukan',
            'day_of_week.required' => 'Hari wajib diisi',
            'day_of_week.min' => 'Hari harus antara 0 (Minggu) sampai 6 (Sabtu)',
            'day_of_week.max' => 'Hari harus antara 0 (Minggu) sampai 6 (Sabtu)',
            'day_of_week.integer' => 'Hari harus berupa angka',
            'start_time.required' => 'Jam mulai wajib diisi',
            'start_time.date_format' => 'Jam mulai harus berupa format H:i',
            'end_time.required' => 'Jam selesai wajib diisi',
            'end_time.date_format' => 'Jam selesai harus berupa format H:i',
            'end_time.after' => 'Jam selesai harus setelah jam mulai',
            'is_active.boolean' => 'Is active harus berupa boolean'
        ]);

        $schedule = BarberSchedule::create($validate);
        return $this->createdResponse($schedule, 'Data jadwal barber berhasil dibuat');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, string $schedule_id)
    {
        $schedule = BarberSchedule::findOrFail($schedule_id);
        $validate = $request->validate([
            'barber_id' => 'required|exists:barbers,id',
            'day_of_week' => 'required|integer|min:0|max:6|unique:barber_schedules,day_of_week,'.$schedule_id.',id,barber_id,'.$request->barber_id,
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_active' => 'nullable|boolean'
        ],[
            'barber_id.required' => 'Barber ID wajib diisi',
            'barber_id.exists' => 'Barber ID tidak ditemukan',
            'day_of_week.required' => 'Hari wajib diisi',
            'day_of_week.integer' => 'Hari harus berupa angka',
            'day_of_week.min' => 'Hari harus antara 0 (Minggu) sampai 6 (Sabtu)',
            'day_of_week.max' => 'Hari harus antara 0 (Minggu) sampai 6 (Sabtu)',
            'start_time.required' => 'Jam mulai wajib diisi',
            'start_time.date_format' => 'Jam mulai harus berupa format H:i',
            'end_time.required' => 'Jam selesai wajib diisi',
            'end_time.date_format' => 'Jam selesai harus berupa format H:i',
            'end_time.after' => 'Jam selesai harus setelah jam mulai',
            'is_active.boolean' => 'Is active harus berupa boolean'
        ]);

        $schedule->update($validate);
        return $this->successResponse($schedule, 'Data jadwal barber berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $schedule_id)
    {
        $schedule = BarberSchedule::findOrFail($schedule_id);
        $schedule->delete();
        return $this->successResponse(null, 'Data jadwal barber berhasil dihapus');
    }
}
