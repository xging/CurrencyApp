<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Services\Http\HttpKernel;
use App\Services\Console\ConsoleKernel;

if (PHP_SAPI === 'cli') {
    // Console
    $kernel = new ConsoleKernel();
    $kernel->handle($argv);
} else {
    // HTTP
    $kernel = new HttpKernel();
    $kernel->handle($_SERVER['REQUEST_URI']);
}