<?php

namespace Tests\Feature;

use App\Services\StoreService;
use Tests\TestCase;

class StoreServiceTest extends TestCase
{
    public function test_store_is_closed_on_sunday()
    {
        $service = new StoreService;
        $status = $service->isOpenNow();
        $this->assertFalse($status['is_open']);
    }
}
