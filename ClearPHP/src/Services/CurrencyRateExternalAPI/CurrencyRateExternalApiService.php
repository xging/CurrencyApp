<?php

namespace App\Services\CurrencyRateExternalAPI;

class CurrencyRateExternalApiService
{
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function fetchExchangeRate(string $from, string $to): ?float
    {
        $url = "https://api.freecurrencyapi.com/v1/latest?apikey={$this->apiKey}&base_currency={$from}";

        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Таймаут в 10 секунд

        $response = curl_exec($ch);

        
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            echo "cURL Error: $error\n";
            return null;
        }

        curl_close($ch);

        
        $data = json_decode($response, true);

        
        if (isset($data['data'][$to])) {
            return (float) $data['data'][$to];
        } else {
            echo "Error: No data available for currency pair {$from} -> {$to}\n";
            return null;
        }
    }
}
