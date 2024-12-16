<?php
namespace App\Services\Console\CurrencyRateConsole;

use App\Services\Console\CurrencyRateConsole\RemovePairCurrencyProcessorService;
use App\Services\Console\CurrencyRateConsole\Interfaces\CurrencyRateConsoleInterface;

class RemovePairCurrencyService implements CurrencyRateConsoleInterface
{
    private $removePairCurrencyProcessorService;

    public function __construct(RemovePairCurrencyProcessorService $removePairCurrencyProcessorService)
    {
        $this->removePairCurrencyProcessorService = $removePairCurrencyProcessorService;
    }

    public function execute(array $args): void
    {
        if (count($args) < 2) {
            echo "Usage: php app.php remove-pair <from_currency> <to_currency>\n";
            return;
        }
        
        echo "*** Deleting currency pairs from the queue.\n";
        $this->removePairCurrencyProcessorService->processAllPairs();

    }
}
