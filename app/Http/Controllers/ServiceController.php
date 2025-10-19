<?php

namespace App\Http\Controllers;

use App\Services\Service\ServiceCRUD;
use App\Services\Service\ServiceDurationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function __construct(
        private ServiceCRUD $service,
    ){}

    public function index()
    {
        $services = $this->service->getAll();

        return Inertia::render('Services', compact('services'));
    }

    public function availableDays(Request $request)
    {
        $durationService = new ServiceDurationService($request->all()['data']);

        return response()->json($durationService->getAvailableDays());
    }

    public function availableTimeSlots(Request $request)
    {
        $data = $request->all()['data'];

        $durationService = new ServiceDurationService($data);

        $day = Carbon::createFromTimeString($data['day']);

        return response()->json($durationService->getAvailableTimeSlots($day));
    }
}
