<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceDurationOption extends Model
{
    protected $fillable = [
        'service_id',
        'duration_minutes',
    ];
}
