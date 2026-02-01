<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DashboardDataService;

class DashboardController extends Controller
{
    public function index(DashboardDataService $service)
    {
        return view('dashboard', [
            'top3'      => $service->top3Teknisi(),
            'top10'     => $service->top10Teknisi(),
            'summary'   => $service->summaryCards(),
            'stoChart'  => $service->completePerSTO(),
            'orderChart'=> $service->orderVsComplete(),
        ]);
    }
}
