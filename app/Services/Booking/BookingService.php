<?php

namespace App\Services\Booking;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingService
{
    private const BUFFER = 30;
    private int $serviceId;

    public function __construct($serviceId = 0)
    {
        $this->serviceId = $serviceId;
    }

    public function store(array $data)
    {
        //TODO: remake via EXCLUDE constraint (PostgreSql)
        DB::transaction(function () use ($data) {
            $startTime = Carbon::createFromTimeString($data['start_time']);
            $endTime = $startTime->copy()
                ->addMinutes(intval($data['option']['duration_minutes']) + self::BUFFER);

            if ($this->checkOverlapping($data, $startTime, $endTime)) {
                throw new \Exception('Этот слот уже занят');
            }

            Booking::create([
                'service_id' => $data['option']['service_id'],
                'service_duration_option_id' => $data['option']['id'],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'user_name' => $data['user_name'],
                'user_phone_number' => $data['user_phone_number'],
            ]);
        });
    }

    private function checkOverlapping($data, $startTime, $endTime)
    {
        return Booking::where('service_id', $data['option']['service_id'])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            })
            ->lockForUpdate()
            ->exists();
    }

    public function getByTheDay($day)
    {
        return Booking::ofService($this->serviceId)
            ->whereDate('start_time', $day->toDateString())
            ->get();
    }
}
