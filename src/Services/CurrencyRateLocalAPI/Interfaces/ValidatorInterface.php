<?php
namespace App\Services\CurrencyRateLocalAPI\Interfaces;
interface ValidatorInterface {
    public function validateCurrencyCode(string $currency): bool;
    public function validateDatetimeFormat(string $datetime): bool;
}
