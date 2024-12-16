<?php

namespace App\Models\DatabaseCRUD;

use App\Database;
use PDO;

class DatabaseCRUDModel implements DatabaseCRUDInterface
{

    public static function saveCurrencyPair(string $from, string $to): bool
    {
        if (!self::checkIfPairExistsBool($from, $to)) {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO currency_pairs (from_currency, to_currency) VALUES (:from, :to)");
            $stmt->execute([':from' => $from, ':to' => $to]);
            return true;
        }
        return false;
    }
    public static function deleteCurrencyPair(string $from, string $to): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM currency_pairs WHERE from_currency = :from AND to_currency = :to ");
        $stmt->execute([':from' => $from, ':to' => $to]);
        return $stmt->rowCount() > 0;
    }

    public static function saveRate(string $from, string $to, float $rate): bool
    {
        if (!self::checkIfRateExistsBool($from, $to)) {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO exchange_rate (from_currency, to_currency, rate) VALUES (:from, :to, :rate)");
            $stmt->execute([':from' => $from, ':to' => $to, ':rate' => $rate]);
            return true;
        }
        return false;
    }

    public static function deleteRates(string $from, string $to): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM exchange_rate WHERE from_currency = :from AND to_currency = :to ");
        $stmt->execute([':from' => $from, ':to' => $to]);

        return $stmt->rowCount() > 0;
    }
    public static function checkIfPairExistsArray(): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT from_currency, to_currency FROM currency_pairs");
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public static function checkIfPairExistsBool(string $from, string $to): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT 1 FROM currency_pairs WHERE from_currency = :from AND to_currency = :to ORDER BY ID DESC LIMIT 1");
        $stmt->execute([':from' => $from, ':to' => $to]);

        return $stmt->rowCount() > 0;
    }

    public static function checkIfRateExistsBool(string $from, string $to): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT 1 FROM exchange_rate WHERE from_currency = :from AND to_currency = :to ORDER BY ID DESC LIMIT 1");
        $stmt->execute([':from' => $from, ':to' => $to]);

        return $stmt->rowCount() > 0;
    }

    public static function showRatesPair(string $from, string $to): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM exchange_rate WHERE from_currency IN(:from,:to) AND to_currency IN (:to,:from) ORDER BY ID DESC LIMIT 2");
        $stmt->execute([':from' => $from, ':to' => $to]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function saveRateHistory(string $from, string $to, float $old_rate, float $new_rate): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO exchange_rate_hist (from_currency, to_currency, old_rate, new_rate) VALUES (:from, :to, :old_rate, :new_rate)");
        $stmt->execute([':from' => $from, ':to' => $to, ':old_rate' => $old_rate, ':new_rate' => $new_rate]);
        return true;
    }

    public static function updateExchangeRate(string $from, string $to, float $new_rate):bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE exchange_rate set rate = :new_rate, last_update_date = NOW() WHERE from_currency = :from AND to_currency = :to");
        $stmt->execute([':from' => $from, ':to' => $to, ':new_rate' => $new_rate]);
        return true;
    }

    public static function getCurrencyRates(string $from, string $to): array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM exchange_rate WHERE from_currency IN(:from,:to) AND to_currency IN (:to,:from) ORDER BY ID DESC LIMIT 2");
        $stmt->execute([':from' => $from, ':to' => $to]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCurrencyRatesHist(string $from, string $to, $date, $dateFormat): array {
        $db = Database::getConnection();

        if($dateFormat === 'datetime') {
            $dateStmt = "DATE_FORMAT(CREATION_DATE, '%Y-%m-%d %H:%i:%s') = DATE_FORMAT(:date, '%Y-%m-%d %H:%i:%s')";
        } else {
            $dateStmt = "DATE_FORMAT(CREATION_DATE, '%M %d %Y') >= DATE_FORMAT(:date, '%M %d %Y')";
        }
        
        
        $stmt = $db->prepare("SELECT * FROM exchange_rate_hist WHERE from_currency IN(:from,:to) AND to_currency IN (:to,:from) AND $dateStmt ORDER BY ID ASC");
        $stmt->execute([':from' => $from, ':to' => $to, ':date' => $date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
