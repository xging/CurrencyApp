<?php

namespace App\Services\Console\CurrencyRateConsole;

use App\Models\DatabaseCRUD\DatabaseCRUDModel;
use App\Services\Console\CurrencyRateConsole\Interfaces\PairProcessorInterface;

class AddPairCurrencyProcessorService implements PairProcessorInterface
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
    
        foreach ($pairs as $index => $pair) {
           $processSinglePair = $this->processSinglePair($pair['from_currency'], $pair['to_currency']);
            
            if(!$processSinglePair) {
                echo "Currency pair ".$pair['from_currency']."-> ".$pair['to_currency']." already exists in the database.\n";
                echo "Currency pair ".$pair['to_currency']."-> ".$pair['from_currency']." already exists in the database.\n";
                break;
            } else if ($index === count($pairs) - 1) {
                echo "Currency pair ".$pair['from_currency']."-> ".$pair['to_currency']." succesfully inserted into database.\n";
                echo "Currency pair ".$pair['to_currency']."-> ".$pair['from_currency']." succesfully inserted into database.\n";
            }
        }
    }

    private function processSinglePair(string $from, string $to): bool
    {
        $saveCurrencyPair = $this->saveCurrencyPairIfNotExist($from, $to);

        if($saveCurrencyPair) {
            return true;
        }
        return false;
    }

    private function saveCurrencyPairIfNotExist(string $from, string $to): bool
    {
        return $this->databaseCRUDModel->saveCurrencyPair($from, $to);
    }
}
