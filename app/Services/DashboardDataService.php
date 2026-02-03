<?php

namespace App\Services;

class DashboardDataService
{
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

            $key = $name.'|'.$unit.'|'.$date;

            $daily[$key]['name']  = $name;
            $daily[$key]['unit']  = $unit;
            $daily[$key]['wonum'] = ($daily[$key]['wonum'] ?? 0) + 1;
        }

        $result = [];

        foreach ($daily as $d) {

            $wonum   = $d['wonum'];
            $percent = min(100, $wonum * 33);

            $status =
                $percent == 100 ? 'Target' :
                ($percent >= 66 ? 'Cukup' : 'Kurang');

            $result[] = [
                'name'    => $d['name'],
                'unit'    => $d['unit'],
                'wonum'   => $wonum,
                'percent' => $percent,
                'status'  => $status,
            ];
        }

        return $result;
    }

    //TOP 3 TEKNISI
    public function top3Teknisi()
    {
        $data = $this->allteknisi();

        usort($data, fn($a,$b) => $b['wonum'] <=> $a['wonum']);

        return array_slice($data, 0, 3);
    }

    //TOP 10 TEKNISI
    public function top10Teknisi()
    {
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

    //ORDER VS COMPLETE
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
