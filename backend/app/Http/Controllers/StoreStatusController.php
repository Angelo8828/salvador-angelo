<?php

namespace App\Http\Controllers;

use App\Services\StoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreStatusController extends Controller
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

    public function checkDate(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        return response()->json($this->storeService->checkDate($request->date));
    }
}
