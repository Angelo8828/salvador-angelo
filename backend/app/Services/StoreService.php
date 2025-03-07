<?php

namespace App\Services;

use App\Models\StoreHour;
use Carbon\Carbon;

class StoreService
{
    public function isOpenNow(): array
    {
        $now = Carbon::now();
        $dayName = $now->format('l');
        $time = $now->format('H:i');

        $storeHours = StoreHour::where('day', $dayName)->first();

        if (! $storeHours || ! $storeHours->open_time || ! $storeHours->close_time) {
            return ['is_open' => false, 'next_opening' => $this->getNextOpening()];
        }

        if ($time >= $storeHours->open_time && $time < $storeHours->close_time) {
            if ($time >= $storeHours->lunch_start && $time < $storeHours->lunch_end) {
                return ['is_open' => false, 'next_opening' => $storeHours->lunch_end];
            }

            return ['is_open' => true, 'next_opening' => null];
        }

        return ['is_open' => false, 'next_opening' => $this->getNextOpening()];
    }

    private function getNextOpening()
    {
        $today = Carbon::now()->format('l');
        $days = StoreHour::pluck('day')->toArray();

        foreach ($days as $day) {
            if ($day > $today) {
                return $day.' at '.StoreHour::where('day', $day)->value('open_time');
            }
        }

        return null;
    }
}
