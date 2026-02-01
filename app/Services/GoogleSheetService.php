<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleSheetService
{
    public function getMappedData($sheetName = 'Sheet1')
    {
        $sheetId = config('services.google_sheets.sheet_id');
        $apiKey  = config('services.google_sheets.api_key');

        $url = "https://sheets.googleapis.com/v4/spreadsheets/{$sheetId}/values/{$sheetName}?key={$apiKey}";

        $response = Http::get($url);

        if ($response->failed()) {
            return [];
        }

        $rows = $response->json()['values'] ?? [];

        if (count($rows) < 2) {
            return [];
        }

        $headers = array_map(function ($header) {
            return strtolower(str_replace(' ', '_', trim($header)));
        }, $rows[0]);

        $data = array_slice($rows, 1);

        return array_map(function ($row) use ($headers) {
            $row = array_pad($row, count($headers), null);
            return array_combine($headers, $row);
        }, $data);
    }
}
