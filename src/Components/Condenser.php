<?php

namespace App\Components;

use App\Repositories\StateRepositoryInterface;

class Condenser
{
    private float $collectedVolume = 0.0;
    private float $alcoholContent = 45.0;
    private StateRepositoryInterface $repo;

    public function __construct(StateRepositoryInterface $repo)
    {
        $this->repo = $repo;
        $this->loadFromRepo();
    }

    public function setCollectedVolume(float $volume): void
    {
        $this->collectedVolume = $volume;
        $this->saveToRepo();
    }

    public function setAlcoholContent(float $content): void
    {
        $this->alcoholContent = $content;
        $this->saveToRepo();
    }

    public function getSensorData(): array
    {
        return ['collectedVolume' => $this->collectedVolume, 'alcoholContent' => $this->alcoholContent];
    }

    private function saveToRepo(): void
    {
        $this->repo->saveComponentState('condenser', $this->getSensorData());
    }

    private function loadFromRepo(): void
    {
        $state = $this->repo->loadComponentState('condenser');
        if ($state) {
            $this->collectedVolume = $state['collectedVolume'] ?? 0.0;
            $this->alcoholContent = $state['alcoholContent'] ?? 45.0;
        }
    }
}
