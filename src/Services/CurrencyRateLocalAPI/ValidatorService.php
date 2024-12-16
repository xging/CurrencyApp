<?php
namespace App\Services\CurrencyRateLocalAPI;
use App\Services\CurrencyRateLocalAPI\Interfaces\ValidatorInterface;
use DateTime;
class ValidatorService implements ValidatorInterface {
    public function validateCurrencyCode(string $currency): bool {
        return preg_match('/^[A-Z]{3}$/', $currency);
    }

    public function validateDatetimeFormat(string $datetime): bool {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
        return $date !== false;
    }
}