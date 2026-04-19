<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;

class GoogleSheetsService
{
    protected $service;

    public function __construct()
    {
        $client = new Client();
        $client->setApplicationName('14RdcXq9dNfgBc-kiTZ9rc17GAigkezlCGgvMZHZHGCg');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(storage_path('app/google/credentials.json'));

        $this->service = new Sheets($client);
    }

    public function append($spreadsheetId, $data)
    {
        $body = new Sheets\ValueRange([
            'values' => [$data]
        ]);

        $params = [
            'valueInputOption' => 'RAW'
        ];

        return $this->service->spreadsheets_values->append(
            $spreadsheetId,
            'Sheet1!A:F',
            $body,
            $params
        );
    }
}
