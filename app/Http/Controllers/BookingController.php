<?php

namespace App\Http\Controllers;

use App\Services\Booking\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ){}

    public function store(Request $request)
    {
        $this->bookingService->store($request->input('data'));

        return response()->json([
            'success' => true,
        ]);
    }
}
