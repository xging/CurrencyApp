<?php

namespace App\Services\HTTP;

use App\Services\HTTP\Routes\RoutesService;

class HTTPKernel
{
    public function handle(string $uri): void
    {

        $uri = parse_url($uri, PHP_URL_PATH);
        $route = new RoutesService();
        $route->requestRoute($uri);
    }
}
