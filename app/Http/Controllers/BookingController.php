<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingStoreRequest;
use App\Services\Booking\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{

    public function store(BookingStoreRequest $request): JsonResponse
    {
        (new BookingService($request->input('data')))->store();

        return response()->json([
            'success' => true,
        ], 201);
    }
}
