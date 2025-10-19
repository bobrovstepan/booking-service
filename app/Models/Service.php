<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'name'
    ];

    protected $with = [ 'options' ];

    public function options(): HasMany
    {
        return $this->hasMany(ServiceDurationOption::class, 'service_id');
    }
}
