<?php

/**
 * Vercel PHP Runtime Entry Point for Laravel
 * Routes all requests through Laravel's public/index.php
 */

// Set the storage path to /tmp (writable on Vercel serverless)
$_ENV['APP_STORAGE'] = '/tmp/storage';

// Bootstrap the Laravel application
require __DIR__ . '/../public/index.php';
