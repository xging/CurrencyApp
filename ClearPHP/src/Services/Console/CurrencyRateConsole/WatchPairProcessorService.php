<?php

namespace App\Services\Console\CurrencyRateConsole;

use App\Models\DatabaseCRUD\DatabaseCRUDModel;
use App\Services\CurrencyRateExternalAPI\CurrencyRateExternalApiService;
use App\Services\Console\CurrencyRateConsole\Interfaces\PairProcessorInterface;

class WatchPairProcessorService implements PairProcessorInterface
{
    private DatabaseCRUDModel $databaseCRUDModel;
    private CurrencyRateExternalApiService $currencyRateApi;

    public function __construct(
        DatabaseCRUDModel $databaseCRUDModel,
        CurrencyRateExternalApiService $currencyRateApi,
    ) {
        $this->databaseCRUDModel = $databaseCRUDModel;
        $this->currencyRateApi = $currencyRateApi;
    }

    public function processAllPairs(): void
    {
        $pairs = $this->databaseCRUDModel->checkIfPairExistsArray();

        if (empty($pairs)) {
            echo "*** No currency pairs found. \n";
            return;
        }

        foreach ($pairs as $pair) {
            $this->processSinglePair($pair['from_currency'], $pair['to_currency']);
        }
    }

    private function processSinglePair(string $from, string $to): void
    {
        $rate = $this->currencyRateApi->fetchExchangeRate($from, $to) ?? 0.0;
        
        if (!$this->databaseCRUDModel->checkIfRateExistsBool($from, $to)) {
            if($rate === 0.0) {
                echo "*** Failed to save rate for: {$from} -> {$to}\n";
                return;
            }
            if ($this->databaseCRUDModel->saveRate($from, $to, $rate)) {
                echo "*** Saved rate: {$from} -> {$to}, rate: {$rate}\n";
            } else {
                echo "*** Failed to save rate for: {$from} -> {$to}\n";
            }
        } elseif ($this->databaseCRUDModel->updateExchangeRate($from, $to, $rate)) {
            echo "*** Updated rate: {$from} -> {$to}, rate: {$rate}\n";
        } else {
            echo "*** Failed to update rate for: {$from} -> {$to}\n";
        }
    }
}
