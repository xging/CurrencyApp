<?php
namespace App\Services\Console\CurrencyRateConsole;

use App\Models\DatabaseCRUD\DatabaseCRUDModel;
use App\Services\Console\CurrencyRateConsole\Interfaces\CurrencyRateConsoleInterface;

class ShowPairRateService implements CurrencyRateConsoleInterface
{
    private $databaseCRUDModel;

    public function __construct(DatabaseCRUDModel $databaseCRUDModel)
    {
        $this->databaseCRUDModel = $databaseCRUDModel;
    }

    public function execute(array $args): void
    {
        if (count($args) < 2) {
            echo "Usage: php consoleApp.php show-pair-rate <from_currency> <to_currency>\n";
            return;
        }

        [$from, $to] = $args;
        $showPairRates = $this->databaseCRUDModel->showRatesPair($from, $to);

        if ($showPairRates) {
            foreach ($showPairRates as $rate) {
                echo "From: " . $rate['from_currency'] . "\n";
                echo "To: " . $rate['to_currency'] . "\n";
                echo "Rate: " . $rate['rate'] . "\n";
                echo str_repeat("-", 40) . "\n";
            }
        } else {
            echo "No rates found for the pair $from -> $to.\n";
        }
    }
}
