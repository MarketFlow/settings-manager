<?php

// Find composer autoloader.
$dir = __DIR__;
while (!file_exists($dir . '/vendor/autoload.php') && $dir != dirname($dir)) {
    $dir = dirname($dir);
}

if (!file_exists($dir . '/vendor/autoload.php')) {
    die("Composer autoload.php not found.");
}

require_once $dir . '/vendor/autoload.php';