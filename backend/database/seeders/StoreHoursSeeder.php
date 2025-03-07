<?php

namespace Database\Seeders;

use App\Models\StoreHour;
use Illuminate\Database\Seeder;

class StoreHoursSeeder extends Seeder
{
    public function run()
    {
        $hours = [
            ['day' => 'Monday', 'open_time' => '08:00', 'close_time' => '16:00', 'lunch_start' => '12:00', 'lunch_end' => '12:45'],
            ['day' => 'Wednesday', 'open_time' => '08:00', 'close_time' => '16:00', 'lunch_start' => '12:00', 'lunch_end' => '12:45'],
            ['day' => 'Friday', 'open_time' => '08:00', 'close_time' => '16:00', 'lunch_start' => '12:00', 'lunch_end' => '12:45'],
            ['day' => 'Saturday', 'open_time' => '08:00', 'close_time' => '16:00', 'lunch_start' => '12:00', 'lunch_end' => '12:45', 'every_other_week' => true],
        ];

        foreach ($hours as $hour) {
            StoreHour::create($hour);
        }
    }
}
