<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Traits\ApiResponse;
use App\Models\TransactionItem;
use App\Models\Transaction;
use App\Models\Booking;
use App\Models\Barber;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    use ApiResponse;
    public function topProduct(){
        $products = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
        ->with('product:id,name,price')
        ->groupBy('product_id')
        ->orderBy('total_sold', 'desc')
        ->limit(10)
        ->get();

        return $this->successResponse($products, 'Top produk berhasil diambil');
    }

    public function topService()
    {
        $services = Booking::select('service_id', DB::raw('COUNT(*) as total_booked'))
            ->with('service:id,name,price,duration_minutes')
            ->where('status', 'completed')
            ->groupBy('service_id')
            ->orderBy('total_booked', 'desc')
            ->limit(10)
            ->get();

        return $this->successResponse($services, 'Top service berhasil diambil');
    }

    public function topRatedBarber()
    {
        $barbers = Barber::select('id', 'name', 'rating')
            ->where('is_active', true)
            ->orderBy('rating', 'desc')
            ->limit(10)
            ->get();

        return $this->successResponse($barbers, 'Top barber berhasil diambil');
    }

    public function topBarber()
    {
        // Pindahkan kondisi status ke dalam JOIN, bukan WHERE
        $barbers = Barber::select('barbers.id', 'barbers.name', 'barbers.rating',
                DB::raw('COUNT(bookings.id) as total_booking'))
            ->leftJoin('bookings', function($join) {
                $join->on('barbers.id', '=', 'bookings.barber_id')
                    ->whereNotIn('bookings.status', ['cancelled']);
            })
            ->where('barbers.is_active', true)
            ->groupBy('barbers.id', 'barbers.name', 'barbers.rating')
            ->orderBy('total_booking', 'desc')
            ->limit(10)
            ->get();

        return $this->successResponse($barbers, 'Top barber berhasil diambil');
    }

    public function revenue(Request $request)
    {
        $period = $request->get('period', 'daily');

        $groupBy = match($period) {
            'monthly' => DB::raw('DATE_FORMAT(created_at, "%Y-%m") as period'),
            'yearly'  => DB::raw('YEAR(created_at) as period'),
            default   => DB::raw('DATE(created_at) as period'),
        };

        $revenue = Transaction::select(
                $groupBy,
                DB::raw('SUM(total_payment) as total_revenue'),
                DB::raw('COUNT(*) as total_transaction')
            )
            ->where('status', 'success')
            ->groupBy('period')
            ->orderBy('period', 'desc')
            ->get();

        return $this->successResponse($revenue, 'Revenue berhasil diambil');
    }

    public function summary(){
        $data = [
            'total_revenue'     => Transaction::where('status', 'success')->sum('total_payment'),
            'total_booking'     => Booking::whereNotIn('status', ['cancelled'])->count(),
            'total_customer'    => User::where('role', 'user')->count(),
            'total_barber'      => Barber::where('is_active', true)->count(),
            'low_stock_product' => Product::where('stock', '<=', 10)->count(),
        ];

        return $this->successResponse($data, 'Summary berhasil diambil');
    }
}
