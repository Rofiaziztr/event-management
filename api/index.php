<?php

/**
 * Vercel Edge Serverless Entry Point
 */

// Ensure /tmp/storage exists for Vercel's read-only filesystem
$tmpStorage = '/tmp/storage';
$directories = ['framework/cache/data', 'framework/sessions', 'framework/testing', 'framework/views', 'logs'];
foreach ($directories as $dir) {
    if (!is_dir("$tmpStorage/$dir")) {
        mkdir("$tmpStorage/$dir", 0777, true);
    }
}

// Override storage path via environment variables
putenv("VIEW_COMPILED_PATH=$tmpStorage/framework/views");
putenv("SESSION_DRIVER=database");
putenv("LOG_CHANNEL=stderr");

require __DIR__ . '/../public/index.php';
