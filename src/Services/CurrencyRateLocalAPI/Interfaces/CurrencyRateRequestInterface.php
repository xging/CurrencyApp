<?php
namespace App\Services\CurrencyRateLocalAPI\Interfaces;
interface CurrencyRateRequestInterface {
    public function getParam(string $name, $default = null);
}
