<?php

namespace App\Services;

class DashboardDataService
{
    public function __construct(
        protected GoogleSheetService $sheet
    ) {}

    protected function data(): array
    {
        return $this->sheet->getMappedData();
    }

    /**
     * Helper status (keterangan)
     */
    protected function status(array $row): string
    {
        return strtoupper(trim($row['keterangan'] ?? ''));
    }

    /**
     * Helper tanggal (Date Modified)
     */
    protected function date(array $row): ?string
    {
        return $row['date_modified'] ?? null;
    }

    // 1️⃣ Grafik STO Complete (PS)
    public function completePerSTO(): array
    {
        $result = [];

        foreach ($this->data() as $row) {
            if ($this->status($row) !== 'COMPLETE') continue;

            $sto = $row['sto'] ?? null;
            if (!$sto) continue;

            $result[$sto] = ($result[$sto] ?? 0) + 1;
        }

        return $result;
    }

    // 2️⃣ Order vs Complete (berdasarkan Date Modified)
    public function orderVsComplete(): array
    {
        $order = [];
        $complete = [];

        foreach ($this->data() as $row) {
            $date = $this->date($row);
            if (!$date) continue;

            // Semua data = Order
            $order[$date] = ($order[$date] ?? 0) + 1;

            // COMPLETE = PS
            if ($this->status($row) === 'COMPLETE') {
                $complete[$date] = ($complete[$date] ?? 0) + 1;
            }
        }

        ksort($order);
        ksort($complete);

        return compact('order', 'complete');
    }

    // 3️⃣ Top 3 Teknisi (berdasarkan COMPLETE)
    public function topTeknisi(): array
    {
        $teknisi = [];

        foreach ($this->data() as $row) {
            if ($this->status($row) !== 'COMPLETE') continue;

            $name = $row['nama_teknisi_1'] ?? null;
            if (!$name) continue;

            $teknisi[$name] = ($teknisi[$name] ?? 0) + 1;
        }

        arsort($teknisi);

        return array_slice($teknisi, 0, 3, true);
    }

    // 4️⃣ PS per STO (PS = COMPLETE)
    public function psPerSTO(): array
    {
        $ps = [];

        foreach ($this->data() as $row) {
            if ($this->status($row) !== 'COMPLETE') continue;

            $sto = $row['sto'] ?? null;
            if (!$sto) continue;

            $ps[$sto] = ($ps[$sto] ?? 0) + 1;
        }

        return $ps;
    }
}
