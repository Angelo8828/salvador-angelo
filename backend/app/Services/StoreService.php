<?php

namespace App\Services;

use App\Models\StoreHour;
use Carbon\Carbon;

class StoreService
{
    /**
     * Check if the store is currently open.
     */
    public function isOpenNow(): array
    {
        $now = Carbon::now();
        $dayName = $now->format('l');
        $currentTime = $now->format('H:i');

        $storeHours = StoreHour::where('day', $dayName)->first();

        if (! $storeHours || ! $storeHours->open_time || ! $storeHours->close_time) {
            return $this->closedResponse($this->getNextOpeningDate());
        }

        if ($this->isWithinOperatingHours($storeHours, $currentTime)) {
            return ['is_open' => true, 'next_opening' => null];
        }

        return $this->closedResponse($this->getNextOpeningDate());
    }

    /**
     * Check if the store is open on a given date.
     */
    public function checkDate(string $date): array
    {
        $date = Carbon::parse($date);
        $dayOfWeek = $date->format('l');

        $storeHours = StoreHour::where('day', $dayOfWeek)->first();

        return $storeHours && $storeHours->open_time && $storeHours->close_time
            ? ['status' => 'open', 'date' => $date->toDateString()]
            : $this->nextOpeningDate($date);
    }

    /**
     * Determine if the store is within operating hours.
     */
    private function isWithinOperatingHours(StoreHour $storeHours, string $currentTime): bool
    {
        if (! $storeHours->open_time || ! $storeHours->close_time) {
            return false;
        }

        if ($currentTime < $storeHours->open_time || $currentTime >= $storeHours->close_time) {
            return false;
        }

        // Check lunch break
        if ($storeHours->lunch_start && $storeHours->lunch_end) {
            if ($currentTime >= $storeHours->lunch_start && $currentTime < $storeHours->lunch_end) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the next opening date after the current time.
     */
    private function getNextOpeningDate(): ?string
    {
        $today = Carbon::now()->format('l');
        $nextOpening = StoreHour::whereNotNull('open_time')
            ->whereNotNull('close_time')
            ->where('day', '>', $today)
            ->orderByRaw("FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->first();

        return $nextOpening ? $nextOpening->day.' at '.$nextOpening->open_time : null;
    }

    /**
     * Get the next opening date relative to a given date.
     */
    private function nextOpeningDate(Carbon $date): array
    {
        $nextOpen = StoreHour::whereNotNull('open_time')
            ->whereNotNull('close_time')
            ->orderByRaw("FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->first();

        if (! $nextOpen) {
            return ['status' => 'closed', 'message' => 'No upcoming opening hours.'];
        }

        $nextOpenDate = Carbon::parse('next '.$nextOpen->day);
        $humanReadable = $nextOpenDate->diffForHumans($date);

        return [
            'status' => 'closed',
            'next_opening' => $nextOpenDate->toDateString(),
            'message' => "The bakery will reopen in $humanReadable.",
        ];
    }

    /**
     * Generate a standard response when the store is closed.
     */
    private function closedResponse(?string $nextOpening): array
    {
        return [
            'is_open' => false,
            'next_opening' => $nextOpening,
        ];
    }
}
