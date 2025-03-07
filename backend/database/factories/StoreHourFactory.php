<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\StoreHour;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StoreHour>
 */
class StoreHourFactory extends Factory
{
    protected $model = StoreHour::class;

    public function definition(): array
    {
        return [
            'day' => $this->faker->randomElement(['Monday', 'Wednesday', 'Friday', 'Saturday']),
            'open_time' => '08:00',
            'close_time' => '16:00',
            'lunch_start' => '12:00',
            'lunch_end' => '12:45',
        ];
    }
}
