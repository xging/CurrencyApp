<?php
namespace App;

final class Config
{
    private static array $config = [
        'db' => [
            'host' => 'clear-mysql-container',
            'dbname' => 'app',
            'username' => 'root',
            'password' => 'root',
        ],
        'currency_api' => [
            'key' => 'YOUR_KEY',
        ],
    ];

    public static function get(): array
    {
        return self::$config;
    }

}
