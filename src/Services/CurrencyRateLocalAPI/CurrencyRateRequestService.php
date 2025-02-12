<?php
namespace App\Services\CurrencyRateLocalAPI;

use App\Services\CurrencyRateLocalAPI\Interfaces\CurrencyRateRequestInterface;
class CurrencyRateRequestService implements CurrencyRateRequestInterface {
    public function getParam(string $name, $default = null) {
        return filter_input(INPUT_GET, $name, FILTER_DEFAULT) ?? $default;
    }
}