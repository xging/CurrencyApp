<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\Database;

$db = new Database();
$pdo = $db->getConnection();

try {
    $pdo->exec("SET GLOBAL log_bin_trust_function_creators = 1");
    $pdo->exec("CREATE TABLE IF NOT EXISTS currency_pairs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        from_currency VARCHAR(3) NOT NULL,
        to_currency VARCHAR(3) NOT NULL,
        creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE (from_currency, to_currency)
    );");

    $pdo->exec("CREATE TABLE IF NOT EXISTS exchange_rate (
        id INT AUTO_INCREMENT PRIMARY KEY,
        from_currency VARCHAR(3) NOT NULL,
        to_currency VARCHAR(3) NOT NULL,
        rate DECIMAL(10, 4) NOT NULL,
        creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_update_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );");

    $pdo->exec("CREATE TABLE IF NOT EXISTS exchange_rate_hist (
        id INT AUTO_INCREMENT PRIMARY KEY,
        from_currency VARCHAR(3) NOT NULL,
        to_currency VARCHAR(3) NOT NULL,
        old_rate DECIMAL(10, 4) NOT NULL,
        new_rate DECIMAL(10, 4) DEFAULT NULL,
        last_update_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );");

    $pdo->exec("CREATE TABLE IF NOT EXISTS  service_status (
        id INT AUTO_INCREMENT PRIMARY KEY,
        service_name VARCHAR(255) NOT NULL UNIQUE,
        status ENUM('running', 'stopped', 'paused', 'error') NOT NULL DEFAULT 'stopped', 
        last_update_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );");


    $pdo->exec("DROP TRIGGER IF EXISTS after_insert_exchange_rate");
    $pdo->exec("
        CREATE TRIGGER after_insert_exchange_rate
        AFTER INSERT ON exchange_rate
        FOR EACH ROW
        BEGIN
        INSERT INTO exchange_rate_hist (from_currency, to_currency, old_rate, new_rate, last_update_date, creation_date)
        VALUES (NEW.from_currency, NEW.to_currency, NEW.rate, 0.0, NOW(),NOW());
        END;
    ");

    $pdo->exec("DROP TRIGGER IF EXISTS after_update_exchange_rate");
    $pdo->exec("
        CREATE TRIGGER after_update_exchange_rate
        AFTER UPDATE ON exchange_rate
        FOR EACH ROW
        BEGIN
        UPDATE exchange_rate_hist
        SET new_rate = NEW.rate, last_update_date = NOW()
        WHERE from_currency = NEW.from_currency AND to_currency = NEW.to_currency
        ORDER BY creation_date DESC
        LIMIT 1;

        INSERT INTO exchange_rate_hist (from_currency, to_currency, old_rate, new_rate, last_update_date, creation_date)
        VALUES (NEW.from_currency, NEW.to_currency, NEW.rate, 0.0, NOW(),NOW());
        END;
    ");

    echo "Tables and trigger created successfully.\n";
} catch (PDOException $e) {
    echo "Error creating tables or trigger: " . $e->getMessage() . "\n";
}
