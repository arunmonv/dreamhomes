<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// App code lives in ./laravel (blocked from the web by its own .htaccess); only this folder is public.
$base = __DIR__.'/laravel';

if (file_exists($maintenance = $base.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $base.'/vendor/autoload.php';

/** @var Application $app */
$app = require_once $base.'/bootstrap/app.php';
$app->usePublicPath(__DIR__); // htdocs is the public path

$app->handleRequest(Request::capture());
