<?php

namespace App\Services;

class DashboardDataService
{
    protected $cachedData = null;
    protected $startDate = null;
    protected $endDate = null;

    public function __construct (
        protected GoogleSheetService $sheet
    ){}

    //DATE FILTER
    public function setDateFilter($start, $end)
    {
        $this->startDate = $start;
        $this->endDate   = $end;
    }

    protected function applyDateFilter(array $data)
    {
    if (!$this->startDate || !$this->endDate) {
        return $data;
    }

    return array_filter($data, function ($row) {
        if (empty($row['date_modified'])) {
            return false;
        }
        $date = date('Y-m-d', strtotime($row['date_modified']));
        return $date >= $this->startDate && $date <= $this->endDate;
    });
    }

    protected function data()
    {
        $rows = $this->sheet->getMappedData();
        return $this->applyDateFilter($rows);
    }

    //ALL TEKNISI (DETAIL)
    public function allteknisi()
    {
        $daily = [];

        // hitung wonum per teknisi per hari
        foreach ($this->data() as $row) {

            $name = $row['nama_teknisi_1'] ?? null;
            $unit = $row['sto'] ?? '-';
            $date = $row['date_modified'] ?? null;

            if (!$name || !$date) continue;

            $date = date('Y-m-d', strtotime($date));

            $key = $name.'|'.$unit.'|'.$date;

            $daily[$key]['name']  = $name;
            $daily[$key]['unit']  = $unit;
            $daily[$key]['wonum'] = ($daily[$key]['wonum'] ?? 0) + 1;
        }

        $summary = [];

        foreach($daily as $d){
            $wonum = $d['wonum'];

            if($wonum >= 3){
                $percent = 100;
            } elseif ($wonum == 2){
                $percent = 66;
            } else {
                $percent = 33;
            }

            $key = $d['name'].'|'.$d['unit'];

            $summary[$key]['name']  = $d['name'];
            $summary[$key]['unit']  = $d['unit'];

            $summary[$key]['wonum'] =
                ($summary[$key]['wonum'] ?? 0) + $wonum;

        
            $summary[$key]['total_percent'] =
                ($summary[$key]['total_percent'] ?? 0) + $percent;

            $summary[$key]['days'] =
                ($summary[$key]['days'] ?? 0) + 1;
        }

        $result = [];

        foreach ($summary as $s) {

            $avgPercent = round($s['total_percent'] / $s['days']);

            if($s['wonum'] >= 60){
                $status = 'Target';
            } elseif ($s['wonum'] >= 30){
                $status = 'cukup';
            } else {
                $status = 'kurang';
            }

            $result[] = [
                'name'    => $s['name'],
                'unit'    => $s['unit'],
                'wonum'   => $s['wonum'],
                'percent' => $avgPercent,
                'status'  => $status,
            ];
        }

        return $result;
    }

    //TOP 3 TEKNISI
    public function top3Teknisi(){
        $data = $this->allteknisi();
        usort($data, fn($a,$b) => $b['wonum'] <=> $a['wonum']);
        return array_slice($data, 0, 3);
    }

    //TOP 10 TEKNISI
    public function top10Teknisi(){
        $data = $this->allteknisi();
        usort($data, fn($a,$b) => $b['wonum'] <=> $a['wonum']);
        return array_slice($data, 0, 10);
    }

    //SUMMARY CARD

    public function summaryCards()
    {
        $rows = $this->data();

        return [
            ['label'=>'Total Vendor',  'value'=>count(array_unique(array_column($rows,'sto')))],
            ['label'=>'Total Order',   'value'=>count($rows)],
            ['label'=>'Total Teknisi','value'=>count(array_unique(array_column($rows,'nama_teknisi_1')))],
        ];
    }

    //STO COMPLETE CHART
    public function completePerSTO()
    {
        $result = [];

        foreach ($this->data() as $row) {
            if (str_contains(strtoupper($row['keterangan'] ?? ''), 'COMPLETE')) {
                $sto = $row['sto'] ?? '-';
                $result[$sto] = ($result[$sto] ?? 0) + 1;
            }
        }

        return $result;
    }

    //ORDER VS UNORDER
    public function orderUnorderTotal()
    {
        $order = 0;
        $unorder = 0;

        foreach ($this->data() as $row) {

            $type = strtoupper($row['order/unorder'] ?? '');

            if (str_contains($type, 'UNORDER')) {
                $unorder++;
            } else {
                $order++;
            }
        }

        return [
            'order'   => $order,
            'unorder' => $unorder,
        ];
    }


    // ORDER PER ZONA
    public function orderPerZona()
    {
        $result = [];

        foreach ($this->data() as $row) {
            $zona = trim($row['sto'] ?? '');

            if ($zona === '') continue;

            $result[$zona] = ($result[$zona] ?? 0) + 1;
        }

        arsort($result);

        return $result;
    }

    //keterangan
    public function statusData()
    {
        $result = [];

        foreach ($this->data() as $row) {
            $ket = strtoupper(trim($row['keterangan'] ?? 'LAINNYA'));

            if ($ket === '') {
                $ket = 'LAINNYA';
            }

            $result[$ket] = ($result[$ket] ?? 0) + 1;
        }

        return $result;
    }

}
