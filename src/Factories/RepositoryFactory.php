<?php

namespace App\Factories;

use App\Repositories\StateRepositoryInterface;
use App\Repositories\RedisStateRepository;
use Exception;

class RepositoryFactory
{
    public static function create(array $config): StateRepositoryInterface
    {
        $type = $config['storageType'];
        $cfg = $config['storageConfig'][$type] ?? [];

        return match ($type) {
            'redis' => new RedisStateRepository($cfg['host'] ?? 'redis', $cfg['port'] ?? 6379),
            default => throw new Exception("Unknown storage type: $type"),
        };
    }
}
