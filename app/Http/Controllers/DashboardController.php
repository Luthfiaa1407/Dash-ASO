<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DashboardDataService;

class DashboardController extends Controller
{
    public function index(DashboardDataService $service)
    {
        return view('dashboard', [
            'stoChart'   => $service->completePerSTO(),
            'orderVsDone'=> $service->orderVsComplete(),
            'topTeknisi' => $service->topTeknisi(),
            'psPerSTO'   => $service->psPerSTO(),
        ]);
    }
}
