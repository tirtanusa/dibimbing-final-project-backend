<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponse;
    public function index(Request $request)
    {
        $query = Transaction::query();

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        return $this->successResponse($query->latest()->paginate($request->get('limit', 10)));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'user_id' => 'required|exists:users,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'payment_method' => 'required|in:cash,debit,credit',
            'items' => 'nullable|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validate) {
            $subtotalProduct = 0;

            // Hitung subtotal produk dari items
            $items = [];
            foreach ($validate['items'] ?? [] as $item) {
                $product = Product::findOrFail($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok produk {$product->name} tidak mencukupi");
                }

                $subtotal = $product->price * $item['quantity'];
                $subtotalProduct += $subtotal;

                // Kurangi stok
                $product->decrement('stock', $item['quantity']);

                $items[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $subtotal,
                ];
            }

            // Ambil subtotal service dari booking jika ada
            $subtotalService = 0;
            if (!empty($validate['booking_id'])) {
                $booking = \App\Models\Booking::findOrFail($validate['booking_id']);
                $subtotalService = $booking->service->price;
            }

            $transaction = Transaction::create([
                'user_id' => $validate['user_id'],
                'booking_id' => $validate['booking_id'] ?? null,
                'subtotal_service' => $subtotalService,
                'subtotal_product' => $subtotalProduct,
                'total_payment' => $subtotalService + $subtotalProduct,
                'payment_method' => $validate['payment_method'],
                'status' => 'pending',
            ]);

            // Insert transaction items
            $transaction->items()->createMany($items);
        });

        return $this->createdResponse(null, 'Transaksi berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = Transaction::findOrFail($id);
        return $this->successResponse($transaction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request, string $id)
    {
        $transaction = Transaction::findOrFail($id);
        $validate = $request->validate([
            'status' => 'required|in:pending,success,failed',
        ],[
            'status.required' => 'Status harus diisi',
            'status.in' => 'Status harus salah satu dari pending, success, failed',
        ]);

        $transaction->update($validate);
        return $this->successResponse($transaction);
    }

    public function myTransaction(Request $request){
        $transaction = Transaction::where('user_id', $request->user()->id)
            ->latest()
            ->paginate($request->get('limit', 10));
        return $this->successResponse($transaction);
    }
}
