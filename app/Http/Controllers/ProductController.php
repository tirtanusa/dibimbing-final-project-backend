<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Models\Product;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponse;
    public function index(Request $request)
    {
        $products = Product::paginate($request->get('limit', 10));
        if($products->isEmpty()){
            return $this->notFoundResponse('Data produk tidak ditemukan');
        }
        return $this->successResponse($products, 'Data produk berhasil diambil');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category' => 'nullable|string',
            'status' => 'in:available,out_of_stock'
        ],[
            'name.required' => 'Nama wajib diisi',
            'price.required' => 'Harga wajib diisi',
            'price.numeric' => 'Harga harus berupa angka',
            'description.string' => 'Deskripsi harus berupa string',
            'stock.required' => 'Stok wajib diisi',
            'stock.numeric' => 'Stok harus berupa angka',
            'category.string' => 'Kategori harus berupa string',
            'status.in' => 'Status harus berupa available atau out_of_stock'
        ]);

        $product = Product::create($validate);
        return $this->createdResponse($product, 'Data produk berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return $this->successResponse($product, 'Data produk berhasil diambil');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $validate = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category' => 'nullable|string',
            'status' => 'in:available,out_of_stock'
        ],[
            'name.required' => 'Nama wajib diisi',
            'price.required' => 'Harga wajib diisi',
            'price.numeric' => 'Harga harus berupa angka',
            'description.string' => 'Deskripsi harus berupa string',
            'stock.required' => 'Stok wajib diisi',
            'stock.integer' => 'Stok harus berupa angka',
            'category.string' => 'Kategori harus berupa string',
            'status.in' => 'Status harus berupa available atau out_of_stock'
        ]);
        $product->update($validate);
        return $this->successResponse($product, 'Data produk berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return $this->successResponse(null, 'Data produk berhasil dihapus');
    }

    // Update Stock
    public function updateStock(Request $request, string $id){
        $product = Product::findOrFail($id);
        
        $validate = $request->validate([
            'stock' => 'required|min:1|integer'
        ],[
            'stock.required' => 'Stok wajib diisi',
            'stock.min' => 'Stok minimal 1',
            'stock.integer' => 'Stok harus berupa angka'
        ]);

        $product->update($validate);
        return $this->successResponse($product, 'Stok produk berhasil diupdate');
    }
}
