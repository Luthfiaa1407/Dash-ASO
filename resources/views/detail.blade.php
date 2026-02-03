@extends('layouts.app')

@section('title', 'Detail Semua Teknisi')

@section('content')

<style>
.detail-card {
    background: #fff;
    border-radius: 14px;
    padding: 24px;
    box-shadow: 0 10px 24px rgba(0,0,0,0.06);
    max-width: 1000px;
    margin: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

th, td {
    padding: 12px;
    border-bottom: 1px solid #E5E7EB;
}

th {
    background: #E31E24;
    color: #fff;
    text-align: left;
}

tbody tr:hover {
    background: #F9FAFB;
}

.text-center { text-align: center; }

.scrollable-table {
    max-height: 600px; /* tinggi maksimal tabel */
    overflow-y: auto;
    display: block;
}

.badge {
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}

.badge.success { background: #22C55E; color: #fff; }
.badge.warning { background: #F59E0B; color: #fff; }
.badge.danger  { background: #EF4444; color: #fff; }

.btn-detail {
    background: #0F2A44;
    color: #fff;
    padding: 8px 22px;
    border-radius: 999px;
    font-size: 12px;
    text-decoration: none;
}

.btn-detail:hover { background: #0c2236; }
</style>

<div class="detail-card">
    <h2 style="margin-bottom:20px;">Daftar Semua Teknisi</h2>

    <div class="scrollable-table">
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
                @forelse($teknisi as $t)
                <tr>
                    <td>{{ $t['name'] }} <small>({{ $t['unit'] }})</small></td>
                    <td class="text-center">{{ $t ['wonum'] }}</td>
                    <td class="text-center">{{ $t['percent'] }}%</td>
                    <td class="text-center">
                        <span class="badge 
                        {{ $t['status']=='Target' ? 'success' : ($t['status']=='Cukup' ? 'warning' : 'danger') }}">
                        {{ $t['status'] }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Data tidak tersedia</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="text-align:center;margin-top:16px;">
        <a href="/dashboard" class="btn-detail">Kembali ke Dashboard</a>
    </div>
</div>

@endsection