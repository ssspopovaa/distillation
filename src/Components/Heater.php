<?php

namespace App\Components;

use App\Repositories\StateRepositoryInterface;

class Heater
{
    private bool $isOn = false;
    private float $temperature = 20.0;
    private StateRepositoryInterface $repo;

    public function __construct(StateRepositoryInterface $repo)
    {
        $this->repo = $repo;
        $this->loadFromRepo();
    }

    public function setTemperature(float $temperature): void
    {
        $this->temperature = $temperature;
        $this->saveToRepo();
    }

    public function setIsOn(bool $isOn): void
    {
        $this->isOn = $isOn;
        $this->saveToRepo();
    }

    public function getSensorData(): array
    {
        return ['temperature' => $this->temperature, 'isOn' => $this->isOn];
    }

    private function saveToRepo(): void
    {
        $this->repo->saveComponentState('heater', $this->getSensorData());
    }

    private function loadFromRepo(): void
    {
        $state = $this->repo->loadComponentState('heater');
        if ($state) {
            $this->temperature = $state['temperature'] ?? 20.0;
            $this->isOn = $state['isOn'] ?? false;
        }
    }
}
