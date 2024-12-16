<?php
namespace App\Services\HTTP\Routes;

class RoutesService
{
    public function requestRoute($uri): void
    {
        if ($uri === '/api/get-currency-rates') {
            require __DIR__ . '/../../../Controllers/API/GetCurrencyRatesAPI.php';
        } else if ($uri === '/api/get-currency-rates-hist') {
            require __DIR__ . '/../../../Controllers/API/GetCurrencyRatesHistAPI.php';
        } else if ($uri === '/db/migration') {
            require __DIR__ . '/../../../../migrations/migration.php';
        } else {
            http_response_code(404);
            echo 'Route not found';
        }
    }

}
