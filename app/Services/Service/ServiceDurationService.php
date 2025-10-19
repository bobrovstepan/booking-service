<?php

namespace App\Services\Service;

use App\Models\Booking;
use App\Services\Booking\BookingService;
use Carbon\Carbon;

class ServiceDurationService
{
    private const BUFFER = 30;
    private const STEP_MINUTE = 30;

    private array $unavailableDays = [
        'Sunday',
    ];

    private const START_HOUR = 10;
    private const START_MINUTE = 0;
    private const END_HOUR = 20;
    private const END_MINUTE = 0;

    private int $serviceId;

    private int $durationId;
    private int $durationMinutes;

    private BookingService $bookingService;

    public function __construct($data)
    {
        $this->serviceId = $data['option']['service_id'];
        $this->durationId = $data['option']['id'];
        $this->durationMinutes = intval($data['option']['duration_minutes']) + self::BUFFER;

        $this->bookingService = new BookingService($this->serviceId);
    }

    public function getAvailableDays(): array
    {
        $now = Carbon::now();
        $weekStart = $now->copy()->startOfWeek();

        $availableDays = [];

        for ($i = 0; $i < 7; $i++) {
            $day = $weekStart->copy()->addDays($i);
            $dayName = $day->format('l');

            if (
                in_array($dayName, $this->unavailableDays)
                || $now->gte($day)
            ) {
                $availableDays[] = [
                    'date' => $day->toDateString(),
                    'available' => false,
                ];
                continue;
            }

            $bookings = $this->bookingService->getByTheDay($day);

            $slots = $this->generateSlots($day);
            $freeSlots = $this->filterSlots($slots, $bookings);

            $availableDays[] = [
                'date' => $day->toDateString(),
                'available' => count($freeSlots) > 0,
            ];
        }

        return $availableDays;
    }

    public function getAvailableTimeSlots(Carbon $day): array
    {
        $bookings = $this->bookingService->getByTheDay($day);

        $slots = $this->generateSlots($day);

        return $this->filterSlots($slots, $bookings);
    }

    private function generateSlots(Carbon $day): array
    {
        $startTime = $day->copy()->setTime(self::START_HOUR, self::START_MINUTE);
        $endTime   = $day->copy()->setTime(self::END_HOUR, self::END_MINUTE);

        $slots = [];
        $cursor = $startTime->copy();

        while ($cursor->lte($endTime)) {
            $slotEnd = $cursor->copy()->addMinutes($this->durationMinutes);
            if ($slotEnd->lte($endTime)) {
                $slots[] = $cursor->toDateTimeString();
            }
            $cursor->addMinutes(self::STEP_MINUTE);
        }

        return $slots;
    }

    private function filterSlots(array $slots, $bookings): array
    {
        return array_values(array_filter($slots, function ($slotStart) use ($bookings) {
            $slotStart = Carbon::parse($slotStart);
            $slotEnd = $slotStart->copy()->addMinutes($this->durationMinutes);

            foreach ($bookings as $booking) {
                if ($slotStart->lt($booking->end_time) && $slotEnd->gt($booking->start_time)) {
                    return false;
                }
            }

            return true;
        }));
    }
}
