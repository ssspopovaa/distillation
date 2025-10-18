<?php

namespace App\Repositories;

use Redis;

class RedisStateRepository implements StateRepositoryInterface
{
    private Redis $redis;

    public function __construct(string $host, int $port)
    {
        $this->redis = new Redis();
        $this->redis->connect($host, $port);
    }

    public function save(array $state, string $key = 'distiller_state'): void
    {
        $this->redis->set($key, json_encode($state));
    }

    public function load(string $key = 'distiller_state'): ?array
    {
        $json = $this->redis->get($key);
        return $json ? json_decode($json, true) : null;
    }

    public function saveComponentState(string $component, array $state): void
    {
        $this->redis->set("component:$component", json_encode($state));
    }

    public function loadComponentState(string $component): ?array
    {
        $json = $this->redis->get("component:$component");
        return $json ? json_decode($json, true) : null;
    }
}
