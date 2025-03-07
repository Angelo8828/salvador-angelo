<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\StoreService;
use App\Models\StoreHour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class StoreServiceTest extends TestCase
{
    use RefreshDatabase; // Refresh the database before each test

    protected StoreService $storeService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->storeService = new StoreService();
    }

    /** @test */
    public function it_returns_false_if_store_is_closed()
    {
        StoreHour::factory()->create([
            'day' => 'Monday',
            'open_time' => '08:00',
            'close_time' => '16:00',
            'lunch_start' => '12:00',
            'lunch_end' => '12:45',
        ]);

        Carbon::setTestNow('Tuesday 10:00');

        $result = $this->storeService->isOpenNow();

        $this->assertFalse($result['is_open']);
        $this->assertNotNull($result['next_opening']);
    }

    /** @test */
    public function it_returns_true_if_store_is_open()
    {
        StoreHour::factory()->create([
            'day' => 'Monday',
            'open_time' => '08:00',
            'close_time' => '16:00',
            'lunch_start' => '12:00',
            'lunch_end' => '12:45',
        ]);

        Carbon::setTestNow('Monday 10:00');

        $result = $this->storeService->isOpenNow();

        $this->assertTrue($result['is_open']);
        $this->assertNull($result['next_opening']);
    }

    /** @test */
    public function it_returns_false_if_store_is_closed_for_lunch()
    {
        StoreHour::factory()->create([
            'day' => 'Monday',
            'open_time' => '08:00',
            'close_time' => '16:00',
            'lunch_start' => '12:00',
            'lunch_end' => '12:45',
        ]);

        Carbon::setTestNow('Monday 12:15');

        $result = $this->storeService->isOpenNow();

        $this->assertFalse($result['is_open']);
        $this->assertEquals('12:45:00', $result['next_opening']);
    }

    /** @test */
    public function it_checks_if_store_is_open_on_a_given_date()
    {
        StoreHour::factory()->create([
            'day' => 'Wednesday',
            'open_time' => '08:00',
            'close_time' => '16:00',
            'lunch_start' => '12:00',
            'lunch_end' => '12:45',
        ]);

        $date = '2025-03-12'; // A Wednesday
        $result = $this->storeService->checkDate($date);

        $this->assertEquals(true, $result['is_open']);
        $this->assertEquals($date, $result['date']);
    }

    /** @test */
    public function it_finds_the_next_opening_date_when_store_is_closed()
    {
        StoreHour::factory()->create([
            'day' => 'Monday',
            'open_time' => '08:00',
            'close_time' => '16:00',
            'lunch_start' => '12:00',
            'lunch_end' => '12:45',
        ]);

        Carbon::setTestNow('Sunday 10:00');

        $result = $this->storeService->isOpenNow();

        $this->assertFalse($result['is_open']);
        $this->assertNotNull($result['next_opening']);
    }
}
