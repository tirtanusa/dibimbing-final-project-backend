<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Models\Service;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponse;
    public function index(Request $request)
    {
        //
        $services = Service::paginate($request->get('limit', 10));

        if($services->isEmpty()){
            return $this->notFoundResponse('Data tidak ditemukan');
        }

        return $this->successResponse($services, 'Data service berhasil diambil');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|min:3',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:10'
        ],[
            'name.required' => 'Nama wajib diisi', 
            'name.string' => 'Nama wajib berupa string',
            'name.min' => 'Nama minimal 3 karakter',
            'description.string' => 'Deskripsi wajib berupa string',
            'price.required' => 'Harga wajib diisi',
            'price.numeric' => 'Harga wajib berupa angka',
            'price.min' => 'Harga minimal 0',
            'duration_minutes.required' => 'Durasi wajib diisi',
            'duration_minutes.integer' => 'Durasi wajib berupa integer',
            'duration_minutes.min' => 'Durasi minimal 10 menit',
        ]);

        $service = Service::create($validate);
        return $this->createdResponse($service, 'Data service berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $service = Service::findOrFail($id);
        return $this->successResponse($service, 'Data service berhasil diambil');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $service = Service::findOrFail($id);
        $validate = $request->validate([
            'name' => 'required|string|min:3',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:10'
        ],[
            'name.required' => 'Nama wajib diisi', 
            'name.string' => 'Nama wajib berupa string',
            'name.min' => 'Nama minimal 3 karakter',
            'description.string' => 'Deskripsi wajib berupa string',
            'price.required' => 'Harga wajib diisi',
            'price.numeric' => 'Harga wajib berupa angka',
            'price.min' => 'Harga minimal 0',
            'duration_minutes.required' => 'Durasi wajib diisi',
            'duration_minutes.integer' => 'Durasi wajib berupa integer',
            'duration_minutes.min' => 'Durasi minimal 10 menit',
        ]);

        $service->update($validate);
        return $this->successResponse($service, 'Data service berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return $this->successResponse(null, 'Data service berhasil dihapus');
    }
}
