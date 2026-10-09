<?php

//require_once __DIR__ . '/server/.env';
$envFile = __DIR__ . '/../.env';

if (!file_exists($envFile))
{
    throw new RuntimeException('.env file not found!');
}

$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line)
{
    $line = trim($line);

    if ($line === '' || str_starts_with($line, '#')) { continue; }

    [$name, $value] = array_pad(explode('=', $line, 2), 2, '');

    putenv("$name=$value");
}

?>