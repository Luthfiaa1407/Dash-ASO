@extends('layouts.app')

@section('title', 'Dashboard Monitoring')

@section('content')

<style>
/* ============ LAYOUT ============ */
.container {
    display: grid;
    grid-template-columns: 420px 1fr;
    gap: 24px;
    padding: 24px;
}

/* ============ CARD ============ */
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

/* ============ TOP 3 ============ */
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

/* ==== FIX ROW PUTIH TOP 3 (INI SAJA YANG DITAMBAHKAN) ==== */
.top3-card tbody tr {
    background: transparent !important;
}

.top3-card tbody tr:hover {
    background: rgba(255,255,255,0.08) !important;
}

/* ============ TABLE ============ */
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

/* ============ BADGE ============ */
.badge {
    padding: 4px 12px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}

.badge.success { background: #22C55E; color: #fff; }
.badge.warning { background: #F59E0B; color: #fff; }
.badge.danger  { background: #EF4444; color: #fff; }

/* ============ BUTTON ============ */
.btn-detail {
    background: #0F2A44;
    color: #fff;
    padding: 8px 22px;
    border-radius: 9999px;
    font-size: 12px;
    text-decoration: none;
    cursor: pointer;
}

.btn-detail:hover { background: #0c2236; }

/* ============ RIGHT ============ */
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

/* BATASI TINGGI CHART */
.chart-card canvas {
    max-height: 260px;
}

/* ============ DATE FILTER ============ */
.date-filter {
    display: flex;
    align-items: flex-end;
    gap: 12px;
}

.date-filter .filter-item {
    display: flex;
    flex-direction: column;
}

.date-filter input {
    padding: 6px 12px;
    border-radius: 9999px;
    border: 1px solid #D1D5DB;
    font-size: 12px;
}

.date-filter button {
    padding: 6px 14px;
    border-radius: 9999px;
    font-size: 12px;
    background: #0F2A44;
    color: #fff;
    border: none;
    cursor: pointer;
}

.date-filter button:hover {
    background: #0c2236;
}
</style>

<div class="container">

    <!-- LEFT -->
    <div>

        <!-- DATE FILTER -->
        <div class="table-card">
            <form method="GET" action="{{ route('Dashboard') }}">
                <div class="date-filter">
                    <div class="filter-item">
                        <label>Start Date:</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="filter-item">
                        <label>End Date:</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <button type="submit">Search</button>
                </div>
            </form>
        </div>

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
                        <td class="text-center">
                            <span class="badge success">{{ $t['percent'] }}%</span>
                        </td>
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
                    @foreach($top10 as $t)
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
            <h4>Total Complete Berdasarkan STO</h4>
            <canvas id="chartSto"></canvas>
        </div>

        <div class="chart-card">
            <h4>Total Order dan Unorder</h4>
            <canvas id="chartOrder"></canvas>
        </div>

        <div class="chart-card">
            <h4>Persentase Keterangan</h4>
            <canvas id="chartStatus"></canvas>
        </div>

        <div class="chart-card">
            <h4>Jumlah Pesanan per Zona</h4>
            <canvas id="chartZona"></canvas>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(chartSto, {
    type: 'bar',
    data: {
        labels: ['BDU','BDK','KBL','KDI','KLA','KLU','MDN','PKP','SBY','YOG'],
        datasets: [{
            data: [8,2,9,4,5,3,22,4,8,3],
            backgroundColor: '#7f1d1d'
        }]
    },
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}}}
});

new Chart(chartOrder, {
    type: 'bar',
    data: {
        labels: ['UNORDER'],
        datasets: [{
            data: [118],
            backgroundColor: '#991b1b'
        }]
    },
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}}}
});

new Chart(chartStatus, {
    type: 'pie',
    data: {
        labels: ['Complete','Cukup','Kurang'],
        datasets: [{
            data: [92,10,7],
            backgroundColor: ['#7f1d1d','#b91c1c','#9ca3af']
        }]
    },
    options:{responsive:true,maintainAspectRatio:false}
});

new Chart(chartZona, {
    type: 'bar',
    data: {
        labels: ['KT','TK','TR','TA','BD','BJ','KBL','KDI','KLU','MDN'],
        datasets: [{
            data: [7,24,10,11,3,9,5,3,7,2],
            backgroundColor: '#0F2A44'
        }]
    },
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}}}
});
</script>

@endsection