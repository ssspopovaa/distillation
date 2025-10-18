<?php

namespace App\Services;

use App\Repositories\StateRepositoryInterface;

class MonitoringService
{
    private StateRepositoryInterface $repo;
    private int $pollInterval;
    private array $config;

    public function __construct(StateRepositoryInterface $repo)
    {
        $this->config = include 'config/config.php';
        $this->repo = $repo;
        $this->pollInterval = $this->config['monitoringInterval'] ?? 10;
    }

    public function start(): void
    {
        while (true) {
            $system = $this->repo->load();
            $isRunning = $system['isRunning'] ?? true;

            $heater = $this->repo->loadComponentState('heater');
            $tank = $this->repo->loadComponentState('mash_tank');
            $condenser = $this->repo->loadComponentState('condenser');

            Logger::log("=== Monitoring as of " . date('H:i:s') . " ===");
            Logger::log("Mode: " . ($this->config['simulationMode'] ? 'Simulation' : 'Real'));

            $isRunning ? Logger::log("SYSTEM RUNNING") : Logger::log("SYSTEM STOPPED");
            Logger::log("Heater: " . json_encode($heater ?? []));
            Logger::log("MashTank: " . json_encode($tank ?? []));
            Logger::log("Condenser: " . json_encode($condenser ?? []));
            Logger::log("=====================================" . PHP_EOL);

            sleep($this->pollInterval);
        }
    }
}
