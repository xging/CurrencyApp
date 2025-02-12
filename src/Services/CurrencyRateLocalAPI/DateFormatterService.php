<?php
namespace App\Services\CurrencyRateLocalAPI;

use App\Services\CurrencyRateLocalAPI\Interfaces\DateFormatterInterface;
use DateTime;
use Exception;

class DateFormatterService implements DateFormatterInterface {
    public function formatDate(string $dateTime, string $time = null): string {
        if ($time) {
            return "$dateTime $time";
        }
        return $dateTime;
    }

    public function createDateTime(string $dateTime): array {
        $dateFormat = 'datetime';
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
        if (!$date) {
            $date = DateTime::createFromFormat('Y-m-d', $dateTime);
            if ($date) {
                $date->setTime(0, 0, 0);
            }
            $dateFormat = "date";
        }

        if (!$date) {
            throw new Exception('Invalid datetime format. Use "Y-m-d" or "Y-m-d H:i:s".');
        }

        return [
            'date' => $date,
            'dateFormat' => $dateFormat
        ];
    }
}
