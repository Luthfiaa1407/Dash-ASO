@extends('layouts.app')

@section('title', 'Dashboard Monitoring')

@section('content')

@php
// ===== MOCK DATA UNTUK FRONTEND SAJA =====
// Top 3 Teknisi Terbaik
$top3 = [
    ['name'=>'RENTA DINATA','unit'=>'GTM','wonum'=>5,'percent'=>100],
    ['name'=>'ANGGI','unit'=>'HOKI','wonum'=>4,'percent'=>100],
    ['name'=>'BUDI','unit'=>'XYZ','wonum'=>4,'percent'=>95],
];

// Top 10 Teknisi (ambil 10 pertama dari data API nanti)
$top10 = [];
for($i=1;$i<=20;$i++){
    $top10[] = [
        'name' => "TEKNISI $i",
        'unit' => "UNIT $i",
        'wonum' => rand(1,5),
        'percent' => rand(30,100),
        'status' => ['Target','Cukup','Kurang'][rand(0,2)]
    ];
}

// Hanya 10 pertama untuk dashboard
$top10_dashboard = array_slice($top10, 0, 10);

// Summary cards
$summary = [
    ['label'=>'Total Vendor','value'=>26],
    ['label'=>'Total Order','value'=>108],
    ['label'=>'Total Teknisi','value'=>50],
];
@endphp

<style>
/* ================= LAYOUT ================= */
.container {
    display: grid;
    grid-template-columns: 420px 1fr; 
    gap: 24px;
    padding: 24px;
}

/* ================= CARD ================= */
.table-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 10px 24px rgba(0,0,0,0.06);
    margin-bottom: 20px;
}

.table-card h4 {
    margin-bottom: 14px;
    color: #0F2A44;
}

/* ================= TOP 3 ================= */
.top3-card {
    background: linear-gradient(135deg, #0F2A44, #1F3C88);
    color: #fff;
}

.top3-card h4 { color: #fff; }

.top3-card table th {
    background: rgba(255,255,255,0.15);
}

.top3-card table td {
    color: #fff;
    border-bottom: 1px solid rgba(255,255,255,0.2);
}

/* ================= TABLE ================= */
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

th {
    background: #E31E24;
    color: white;
    padding: 12px;
    text-align: left;
}

td {
    padding: 12px;
    border-bottom: 1px solid #E5E7EB;
}

tbody tr:hover {
    background: #F9FAFB;
}

.text-center { text-align: center; }

/* ================= BADGE ================= */
.badge {
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
    display: inline-block;
}

.badge.success { background: #22C55E; color: #fff; }
.badge.warning { background: #F59E0B; color: #fff; }
.badge.danger  { background: #EF4444; color: #fff; }

/* ================= BUTTON ================= */
.btn-detail {
    background: #0F2A44;
    color: #fff;
    padding: 8px 22px;
    border-radius: 999px;
    font-size: 12px;
    text-decoration: none;
}

.btn-detail:hover { background: #0c2236; }

/* ================= RIGHT ================= */
.main {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.summary-card {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    text-align: center;
    box-shadow: 0 10px 24px rgba(0,0,0,0.06);
}

.summary-card h2 {
    font-size: 36px;
    color: #E31E24;
}

.summary-card span {
    font-size: 14px;
    color: #6B7280;
}

.chart-card {
    grid-column: span 3;
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 10px 24px rgba(0,0,0,0.06);
}

.chart-placeholder {
    height: 200px;
    background: linear-gradient(135deg, #F3F4F6, #E5E7EB);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6B7280;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 992px) {
    .container { grid-template-columns: 1fr; }
    .main { grid-template-columns: 1fr; }
    .chart-card { grid-column: span 1; }
}
</style>

<div class="container">

    <!-- LEFT -->
    <div>

        <!-- TOP 3 -->
        <div class="table-card top3-card">
            <h4>Top 3 Teknisi Terbaik</h4>
            <table>
                <thead>
                    <tr>
                        <th>Nama Teknisi</th>
                        <th class="text-center">Wonum</th>
                        <th class="text-center">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($top3 as $t)
                    <tr>
                        <td>{{ $t['name'] }} <small>({{ $t['unit'] }})</small></td>
                        <td class="text-center">{{ $t['wonum'] }}</td>
                        <td class="text-center"><span class="badge success">{{ $t['percent'] }}%</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- TOP 10 -->
        <div class="table-card">
            <h4>Top 10 Teknisi</h4>
            <table>
                <thead>
                    <tr>
                        <th>Nama Teknisi</th>
                        <th class="text-center">Wonum</th>
                        <th class="text-center">Persentase</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($top10_dashboard as $t)
                    <tr>
                        <td>{{ $t['name'] }} <small>({{ $t['unit'] }})</small></td>
                        <td class="text-center">{{ $t['wonum'] }}</td>
                        <td class="text-center">{{ $t['percent'] }}%</td>
                        <td class="text-center">
                            <span class="badge {{ $t['status']=='Target'?'success':($t['status']=='Cukup'?'warning':'danger') }}">
                                {{ $t['status'] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="text-align:center;margin-top:16px;">
                <a href="/detail" class="btn-detail">Lihat Detail</a>
            </div>
        </div>

    </div>

    <!-- RIGHT -->
    <div class="main">
        @foreach($summary as $s)
        <div class="summary-card">
            <h2>{{ $s['value'] }}</h2>
            <span>{{ $s['label'] }}</span>
        </div>
        @endforeach

        <div class="chart-card">
            <h4>Total Closing Berdasarkan STO</h4>
            <div class="chart-placeholder">Chart Area</div>
        </div>

        <div class="chart-card">
            <h4>Jumlah Order per Vendor</h4>
            <div class="chart-placeholder">Chart Area</div>
        </div>

        <div class="chart-card">
            <h4>Presentase Pekerjaan</h4>
            <div class="chart-placeholder">Chart Area</div>
        </div>
    </div>

</div>

@endsection