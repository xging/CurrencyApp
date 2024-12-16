<?php
namespace App\Services\Console\CurrencyRateConsole;

use App\Services\Console\CurrencyRateConsole\Interfaces\CurrencyRateConsoleInterface;
class AddPairCurrencyService implements CurrencyRateConsoleInterface
{

    private $addPairCurrencyProcessorService;
    public function __construct(AddPairCurrencyProcessorService $addPairCurrencyProcessorService)
    {
        $this->addPairCurrencyProcessorService = $addPairCurrencyProcessorService;
    }

    public function execute(array $args): void
    {
        if (count($args) < 2) {
            echo "Usage: php app.php remove-pair <from_currency> <to_currency>\n";
            return;
        }

        echo "*** Adding currency pairs into the queue.\n";
        $this->addPairCurrencyProcessorService->processAllPairs();
    }
}
