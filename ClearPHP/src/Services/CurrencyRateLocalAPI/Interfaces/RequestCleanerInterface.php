<?php
namespace App\Services\CurrencyRateLocalAPI\Interfaces;
interface RequestCleanerInterface {
    public function clean(string $data): string;
    public function cleanArray(array $data): array;
}