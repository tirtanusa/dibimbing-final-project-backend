<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class BarberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponse;
    public function index(Request $request)
    {
        $barbers = Barber::paginate($request->get('limit', 10));
        return $this->successResponse($barbers, 'Data barber berhasil diambil');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string',
            'bio' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'is_active' => 'nullable|boolean'
        ],[
            'name.required' => 'Nama wajib diisi',
            'name.string' => 'Nama harus berupa string',
            'bio.string' => 'Bio harus berupa string',
            'rating.numeric' => 'Rating harus berupa angka',
            'rating.min' => 'Rating minimal 0',
            'rating.max' => 'Rating maksimal 5',
            'is_active.boolean' => 'Is active harus berupa boolean'
        ]);

        $barber = Barber::create($validated);
        return $this->createdResponse($barber,'Data barber berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $barber = Barber::findOrFail($id);
        return $this->successResponse($barber, 'Data barber berhasil diambil');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $barber = Barber::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'bio' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'is_active' => 'nullable|boolean'
        ],[
            'name.required' => 'Nama wajib diisi',
            'name.string' => 'Nama harus berupa string',
            'bio.string' => 'Bio harus berupa string',
            'rating.numeric' => 'Rating harus berupa angka',
            'rating.min' => 'Rating minimal 0',
            'rating.max' => 'Rating maksimal 5',
            'is_active.boolean' => 'Is active harus berupa boolean'
        ]);

        
        
        $barber->update($validated);
        return $this->successResponse($barber, 'Data barber berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $barber = Barber::findOrFail($id);
        $barber->delete();
        return $this->successResponse(null, 'Data barber berhasil dihapus');
    }
}
