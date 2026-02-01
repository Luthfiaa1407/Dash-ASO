<?php

namespace App\Services;

class DashboardDataService
{
    public function __construct(
        protected GoogleSheetService $sheet
    ) {}

    protected function data()
    {
        return $this->sheet->getMappedData();
    }

    // TOP 3 TEKNISI
    public function top3Teknisi()
    {
        $result = [];

        foreach ($this->data() as $row) {
            if (str_contains(strtoupper($row['keterangan'] ?? ''), 'COMPLETE')) {
                $name = $row['nama_teknisi_1'] ?? '-';
                $unit = $row['sto'] ?? '-';
                $key = $name.'|'.$unit;

                $result[$key]['name']  = $name;
                $result[$key]['unit']  = $unit;
                $result[$key]['wonum'] = ($result[$key]['wonum'] ?? 0) + 1;
            }
        }

        usort($result, fn($a,$b) => $b['wonum'] <=> $a['wonum']);

        return array_slice(array_map(function($r){
            $r['percent'] = 100;
            return $r;
        }, $result), 0, 3);
    }

    // TOP 10 TEKNISI
    public function top10Teknisi()
    {
        $result = [];

        foreach ($this->data() as $row) {
            $name = $row['nama_teknisi_1'] ?? '-';
            $unit = $row['sto'] ?? '-';
            $key  = $name.'|'.$unit;

            $result[$key]['name']  = $name;
            $result[$key]['unit']  = $unit;
            $result[$key]['wonum'] = ($result[$key]['wonum'] ?? 0) + 1;
        }

        usort($result, fn($a,$b) => $b['wonum'] <=> $a['wonum']);

        return array_slice(array_map(function($r){
            $r['percent'] = rand(60,100);
            $r['status']  = $r['percent'] >= 90 ? 'Target' : ($r['percent'] >= 70 ? 'Cukup' : 'Kurang');
            return $r;
        }, $result), 0, 10);
    }

    // SUMMARY CARD
    public function summaryCards()
    {
        $rows = $this->data();

        return [
            ['label'=>'Total Vendor', 'value'=>count(array_unique(array_column($rows,'sto')))],
            ['label'=>'Total Order', 'value'=>count($rows)],
            ['label'=>'Total Teknisi', 'value'=>count(array_unique(array_column($rows,'nama_teknisi_1')))],
        ];
    }

    // CHART STO COMPLETE
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

    // ORDER VS COMPLETE
    public function orderVsComplete()
    {
        $order = [];
        $complete = [];

        foreach ($this->data() as $row) {
            $date = $row['date_modified'] ?? '-';
            $order[$date] = ($order[$date] ?? 0) + 1;

            if (str_contains(strtoupper($row['keterangan'] ?? ''), 'COMPLETE')) {
                $complete[$date] = ($complete[$date] ?? 0) + 1;
            }
        }

        return compact('order','complete');
    }
}
