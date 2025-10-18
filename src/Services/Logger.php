<?php

namespace App\Services;

class Logger
{
    public static function log(string $message): void
    {
        echo '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
    }
}
