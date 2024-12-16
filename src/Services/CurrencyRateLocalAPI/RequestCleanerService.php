<?php
namespace App\Services\CurrencyRateLocalAPI;

use App\Services\CurrencyRateLocalAPI\Interfaces\RequestCleanerInterface;

class RequestCleanerService implements RequestCleanerInterface{
    public function clean(string $data): string {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    public function cleanArray(array $data): array {
        return array_map(fn($value) => $this->clean($value), $data);
    }
}