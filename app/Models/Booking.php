<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'service_id',
        'service_duration_option_id',
        'start_time',
        'end_time',
        'user_name',
        'user_phone_number',
    ];

    public function scopeOfService($query, $serviceId)
    {
        return $query->where('service_id', $serviceId);
    }
}
