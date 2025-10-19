<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceDurationOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Поездка на квадроцикле',
                'duration_options' => [
                    30,
                    60
                ]
            ],
            [
                'name' => 'Тур на эндуро',
                'duration_options' => [
                    60,
                    120
                ],
            ]
        ];

        foreach ($services as $service) {
            $createdService = Service::updateOrCreate(
                [ 'name' => $service['name'] ],
                []
            );

            foreach ($service['duration_options'] as $duration) {
                ServiceDurationOption::updateOrCreate([
                    'service_id' => $createdService->id,
                    'duration_minutes' => $duration
                ], []);
            }
        }
    }
}
