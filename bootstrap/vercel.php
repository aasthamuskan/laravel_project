<?php

/**
 * Vercel-compatible Storage Bootstrap
 * Redirects storage to /tmp when on Vercel serverless
 */

use Illuminate\Foundation\Application;

// On Vercel, filesystem is read-only except /tmp
if (isset($_ENV['VERCEL']) || getenv('VERCEL')) {
    $tmpStorage = '/tmp/laravel-storage';
    $dirs = [
        "$tmpStorage/app/public",
        "$tmpStorage/framework/cache/data",
        "$tmpStorage/framework/sessions",
        "$tmpStorage/framework/testing",
        "$tmpStorage/framework/views",
        "$tmpStorage/logs",
    ];
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
    }
}

$app = require_once __DIR__.'/../bootstrap/app.php';
