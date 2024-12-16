<?php
namespace App\Services\Console\CurrencyRateConsole;
use App\Models\DatabaseCRUD\DatabaseCRUDModel;
use App\Services\Console\CurrencyRateConsole\RemovePairCurrencyService;
use App\Services\CurrencyRateExternalAPI\CurrencyRateExternalApiService;
use App\Services\Console\CurrencyRateConsole\AddPairCurrencyProcessorService;
use App\Services\Console\CurrencyRateConsole\RemovePairCurrencyProcessorService;
use App\Services\Console\CurrencyRateConsole\WatchPairProcessorService;
use App\Services\Console\CurrencyRateConsole\ShowPairRateService;

use App\Config;
class CommandFactoryService
{
    public function createCommandRegistry(array $args): CommandRegistryService
    {
        $apiKey =  Config::get()['currency_api']['key'];
        [$from, $to] = $args ? $args : ['', ''];
        $argsFromConsole = [
            ['from_currency' => $from, 'to_currency' => $to],
            ['from_currency' => $to, 'to_currency' => $from]
        ];

        $databaseCRUDModel = new DatabaseCRUDModel();
        $currencyRateApi = new CurrencyRateExternalApiService(apiKey: $apiKey);
        $registry = new CommandRegistryService();

        $registry->register('add-pair', new AddPairCurrencyService(
            new AddPairCurrencyProcessorService($databaseCRUDModel, $argsFromConsole)
        ));

        $registry->register('remove-pair', new RemovePairCurrencyService(
            new RemovePairCurrencyProcessorService($databaseCRUDModel, $argsFromConsole)
        ));

        $registry->register('show-pair-rate', new ShowPairRateService($databaseCRUDModel));

        $registry->register('watch-pair', new WatchPairService(
            new WatchPairProcessorService($databaseCRUDModel, $currencyRateApi)
        ));

        return $registry;
    }
}