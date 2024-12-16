<?php
namespace App;

use PDO;
use PDOException;
use App\Config;

final class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $db = Config::get()['db'];
            self::setConnection($db);
        }

        return self::$connection;
    }

    private static function setConnection(array $db): void
    {
        try {
            self::$connection = new PDO(
                "mysql:host={$db['host']};dbname={$db['dbname']}",
                $db['username'],
                $db['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            self::$connection->query("SELECT 1");

        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            die("Database connection error. Please check logs for details.");
        }
    }
}
