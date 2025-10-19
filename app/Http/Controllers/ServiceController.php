<?php

namespace App\Http\Controllers;

use App\Services\Service\ServiceCRUD;
use App\Services\Service\ServiceDurationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function __construct(
        private ServiceCRUD $service,
    ){}

    public function index(): \Inertia\Response
    {
        $services = $this->service->getAll();

        return Inertia::render('Services', compact('services'));
    }

    public function availableDays(Request $request): JsonResponse
    {
        $durationService = new ServiceDurationService($request->input('data'));

        return response()->json($durationService->getAvailableDays());
    }

    public function availableTimeSlots(Request $request): JsonResponse
    {
        $data = $request->input('data');

        $durationService = new ServiceDurationService($data);

        $day = Carbon::parse($data['day']);

        return response()->json($durationService->getAvailableTimeSlots($day));
    }
}
