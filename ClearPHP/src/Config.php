<?php
namespace App;

final class Config
{
    private static array $config = [
        'db' => [
            'host' => 'clear-mysql-container',
            'dbname' => 'app',
            'username' => 'user',
            'password' => 'password',
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
