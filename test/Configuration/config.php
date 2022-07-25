<?php

use Brezgalov\ExtApiLogger\Logger\LoggerComponent;
use Brezgalov\ExtApiLogger\LogsStorageDb\LogsStorageDb;

$dir = __DIR__;
$dbFile = __DIR__ . '/db.local.php';

if (!is_file($dbFile)) {
    throw new Exception("You should create local db connection file \"{$dbFile}\" to perform tests");
}

return [
    'id' => 'external-logger-test-app',
    'name' => 'External logger test app',
    'basePath' => $dir,
    'components' => [
        'db' => require $dbFile,
    ],
];