<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DashboardDataService;

class DashboardController extends Controller
{
    public function index(Request $request, DashboardDataService $service){

        if ($request->filled('start_date') && $request->filled('end_date')) {
        $service->setDateFilter(
            $request->start_date,
            $request->end_date
        );
        }

        return view('dashboard', [
            'top3'     => $service->top3Teknisi(),
            'top10'    => $service->top10Teknisi(),
            'summary'  => $service->summaryCards(),
            'stoChart' => $service->completePerSTO(),
            'orderVs'  => $service->orderVsComplete(),
        ]);

        return view('detail', [
            'teknisi' => $service->allteknisi()
        ]);
    }
}
