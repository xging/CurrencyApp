<?php
namespace App\Services\CurrencyRateLocalAPI\Interfaces;

use DateTime;

interface DateFormatterInterface {
    public function formatDate(string $dateTime, string $time = null): string;
    public function createDateTime(string $dateTime): array;
}
