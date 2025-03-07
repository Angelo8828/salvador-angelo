<?php

namespace App\Http\Controllers;

use App\Services\StoreService;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    protected $storeService;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function status(): JsonResponse
    {
        return response()->json($this->storeService->isOpenNow());
    }
}
