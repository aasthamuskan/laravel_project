<?php

/**
 * Vercel PHP Runtime Entry Point for Laravel
 */

// Redirect storage to /tmp on Vercel (read-only filesystem)
if (!empty($_ENV['VERCEL']) || !empty(getenv('VERCEL'))) {
    $tmpStorage = '/tmp/laravel-storage';
    foreach ([
        "$tmpStorage/app/public",
        "$tmpStorage/framework/cache/data",
        "$tmpStorage/framework/sessions",
        "$tmpStorage/framework/testing",
        "$tmpStorage/framework/views",
        "$tmpStorage/logs",
    ] as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
    }
    $_ENV['APP_STORAGE_PATH'] = $tmpStorage;
    putenv("APP_STORAGE_PATH=$tmpStorage");
}

// Define base path
define('LARAVEL_START', microtime(true));

// Boot Laravel
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// Override storage path to /tmp if on Vercel
if (!empty($_ENV['VERCEL']) || !empty(getenv('VERCEL'))) {
    $app->useStoragePath('/tmp/laravel-storage');
}

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);
