<?php

namespace App\Services;

use App\Models\StoreHour;
use Carbon\Carbon;

class StoreService
{
    /**
     * Check if the store is open now.
     *
     * @return array{is_open: bool, next_opening: string|null}
     */
    public function isOpenNow(): array
    {
        $now = Carbon::now();
        $dayName = $now->format('l');
        $time = $now->format('H:i');

        $storeHours = StoreHour::where('day', $dayName)->first();

        if (! $storeHours || ! $storeHours->open_time || ! $storeHours->close_time) {
            return ['is_open' => false, 'next_opening' => $this->getNextOpening($dayName)];
        }

        $isOpen = $time >= $storeHours->open_time && $time < $storeHours->close_time;

        if ($isOpen && $time >= $storeHours->lunch_start && $time < $storeHours->lunch_end) {
            return ['is_open' => false, 'next_opening' => $storeHours->lunch_end];
        }

        return ['is_open' => $isOpen, 'next_opening' => $isOpen ? null : $this->getNextOpening($dayName)];
    }

    /**
     * Check if the store is open on a given date.
     *
     * @param  string  $date  A date string (e.g., "2025-03-07")
     * @return array{is_open: bool, date: string}|array{is_open: false, next_opening: string|null, message: string}
     */
    public function checkDate(string $date): array
    {
        $parsedDate = Carbon::parse($date);
        $dayOfWeek = $parsedDate->format('l');

        $storeHours = StoreHour::where('day', $dayOfWeek)->first();

        if ($storeHours) {
            return ['is_open' => true, 'date' => $parsedDate->toDateString()];
        }

        return $this->nextOpeningDate($parsedDate);
    }

    /**
     * Get the next opening day and time.
     */
    private function getNextOpening(string $currentDay): ?string
    {
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $sortedDays = collect($daysOfWeek)
            ->filter(fn ($day) => StoreHour::where('day', $day)->exists())
            ->values();

        $currentIndex = $sortedDays->search($currentDay);
        $nextDay = $sortedDays->get(($currentIndex + 1) % $sortedDays->count());

        return $nextDay ? $nextDay.' at '.StoreHour::where('day', $nextDay)->value('open_time') : null;
    }

    /**
     * Find the next opening date if the store is closed on the given date.
     *
     * @return array{is_open: false, next_opening: string|null, message: string}
     */
    private function nextOpeningDate(Carbon $date): array
    {
        $dayOfWeek = $date->format('l');
        $nextOpening = $this->getNextOpening($dayOfWeek);

        return [
            'is_open' => false,
            'next_opening' => $nextOpening,
            'message' => $nextOpening ? "The bakery will reopen on $nextOpening." : 'No upcoming opening hours.',
        ];
    }
}
