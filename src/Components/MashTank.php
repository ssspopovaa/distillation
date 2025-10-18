<?php

namespace App\Components;

use App\Repositories\StateRepositoryInterface;

class MashTank
{
    private float $mashLevel = 100.0;
    private StateRepositoryInterface $repo;

    public function __construct(StateRepositoryInterface $repo)
    {
        $this->repo = $repo;
        $this->loadFromRepo();
    }

    public function setMashLevel(float $level): void
    {
        $this->mashLevel = $level;
        $this->saveToRepo();
    }

    public function getSensorData(): array
    {
        return ['mashLevel' => $this->mashLevel];
    }

    private function saveToRepo(): void
    {
        $this->repo->saveComponentState('mash_tank', $this->getSensorData());
    }

    private function loadFromRepo(): void
    {
        $state = $this->repo->loadComponentState('mash_tank');
        if ($state) {
            $this->mashLevel = $state['mashLevel'] ?? 100.0;
        }
    }
}
