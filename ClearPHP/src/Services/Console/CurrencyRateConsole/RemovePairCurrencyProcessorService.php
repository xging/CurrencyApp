<?php

namespace App\Services\Console\CurrencyRateConsole;

use App\Models\DatabaseCRUD\DatabaseCRUDModel;
use App\Services\Console\CurrencyRateConsole\Interfaces\PairProcessorInterface;

class RemovePairCurrencyProcessorService implements PairProcessorInterface
{
    private DatabaseCRUDModel $databaseCRUDModel;
    private array $argsFromConsole; 

    public function __construct(
        DatabaseCRUDModel $databaseCRUDModel,
        array $argsFromConsole
    ) {
        $this->databaseCRUDModel = $databaseCRUDModel;
        $this->argsFromConsole = $argsFromConsole;
    }

    public function processAllPairs(): void
    {
        $pairs = $this->argsFromConsole;
        
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
        $deleteCurrencyPair = $this->deleteCurrencyPair($from, $to);

        if($deleteCurrencyPair) {
            echo "Currency pairs $from -> $to and $to -> $from have been deleted.\n";
        } else {
            echo "Currency pairs $from -> $to and $to -> $from have not been deleted.\n";
        }
    }

    private function deleteCurrencyPair (string $from, string $to): bool
    {  
        $this->deleteExchangeRatePair($from, $to);
        return $this->databaseCRUDModel->deleteCurrencyPair($from, $to);
        
    }

    private function deleteExchangeRatePair (string $from, string $to): bool {
        return $this->databaseCRUDModel->deleteRates($from, $to);
    }
}
