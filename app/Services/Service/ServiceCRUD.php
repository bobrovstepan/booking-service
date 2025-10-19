<?php

namespace App\Services\Service;

use App\Models\Service;
use Illuminate\Support\Collection;

class ServiceCRUD
{
    public function getAll(): Collection
    {
        return Service::all();
    }
}
