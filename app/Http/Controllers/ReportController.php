<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Traits\ApiResponse;
use App\Models\TransactionItem;
use App\Models\Booking;
use App\Models\Barber;
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
            ->where('status', ['completed'])
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

    public function revenue()
    {
        $revenue = Booking::select('status', DB::raw('SUM(total_price) as total_revenue'))
            ->groupBy('status')
            ->get();

        return $this->successResponse($revenue, 'Revenue berhasil diambil');
    }
}
