<?php
namespace App\Models\DatabaseCRUD;

interface DatabaseCRUDInterface{
    public static function saveCurrencyPair(string $from, string $to): bool;
    public static function deleteCurrencyPair(string $from, string $to): bool;
    public static function saveRate(string $from, string $to, float $rate): bool;
    public static function deleteRates(string $from, string $to): bool;
    public static function checkIfPairExistsArray():array;
    public static function checkIfPairExistsBool(string $from, string $to): bool;
    public static function checkIfRateExistsBool(string $from, string $to): bool;
    public static function showRatesPair(string $from, string $to): array;
    public static function saveRateHistory(string $from, string $to, float $old_rate, float $new_rate):bool;
    public static function updateExchangeRate(string $from, string $to, float $new_rate):bool;
    public static function getCurrencyRates(string $from, string $to): array;
    public static function getCurrencyRatesHist(string $from, string $to, $date, $dateFormat): array;
}