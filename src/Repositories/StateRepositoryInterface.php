<?php

namespace App\Repositories;

interface StateRepositoryInterface
{
    public function save(array $state, string $key = 'distiller_state'): void;

    public function load(string $key = 'distiller_state'): ?array;

    public function saveComponentState(string $component, array $state): void;

    public function loadComponentState(string $component): ?array;
}
