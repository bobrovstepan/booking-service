<?php

namespace App\Services\Booking;

use App\Exceptions\SlotAlreadyBookedException;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BookingService
{
    private const BUFFER_MINUTES = 30;

    private ?int $serviceId;

    private ?int $optionId;

    private ?int $durationMinutes;

    private ?Carbon $start;

    private ?Carbon $end;

    private ?string $userName;

    private ?string $phoneNumber;

    public function __construct(array $data)
    {
        $this->serviceId = $data['option']['service_id'] ?? null;
        $this->optionId = $data['option']['id'] ?? null;
        $this->durationMinutes = $data['option']['duration_minutes'] ?? null;

        $this->start = isset($data['start_time']) ? Carbon::createFromTimeString($data['start_time']) : null;
        $this->end = $this->start?->copy()
            ->addMinutes(intval($this->durationMinutes) + self::BUFFER_MINUTES);

        $this->userName = $data['user']['name'] ?? null;
        $this->phoneNumber = $data['user']['phone_number'] ?? null;
    }

    public function store(): void
    {
        //TODO: remake via EXCLUDE constraint (PostgreSql)
        DB::transaction(function () {

            if ($this->checkOverlapping()) {
                throw new SlotAlreadyBookedException();
            }

            Booking::create([
                'service_id' => $this->serviceId,
                'service_duration_option_id' => $this->optionId,
                'start_time' => $this->start,
                'end_time' => $this->end,
                'user_name' => $this->userName,
                'user_phone_number' => $this->phoneNumber,
            ]);
        });
    }

    private function checkOverlapping(): bool
    {
        return Booking::ofService($this->serviceId)
            ->where('start_time', '<', $this->end)
            ->where('end_time', '>', $this->start)
            ->lockForUpdate()
            ->exists();
    }

    public function getByTheWeek(Carbon $week_start, Carbon $week_end): Collection
    {
        return Booking::ofService($this->serviceId)
            ->whereBetween('start_time', [$week_start, $week_end])
            ->get();
    }

    public function getByTheDay(Carbon $day): Collection
    {
        return Booking::ofService($this->serviceId)
            ->whereDate('start_time', $day->toDateString())
            ->get();
    }
}
