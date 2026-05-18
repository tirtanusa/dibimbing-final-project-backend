<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarberScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = BarberSchedule::paginate(10);
        return $this->successResponse($schedules, 'Data jadwal barber berhasil diambil');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schedule = BarberSchedule::findOrFail($id);
        return $this->successResponse($schedule, 'Data jadwal barber berhasil diambil');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schedule = BarberSchedule::findOrFail($id);
        $schedule->delete();
        return $this->successResponse(null, 'Data jadwal barber berhasil dihapus');
    }
}
