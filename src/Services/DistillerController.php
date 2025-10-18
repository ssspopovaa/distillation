<?php

namespace App\Services;

use App\Components\Heater;
use App\Components\MashTank;
use App\Components\Condenser;
use App\Repositories\StateRepositoryInterface;

class DistillerController
{
    private Heater $heater;
    private MashTank $mashTank;
    private Condenser $condenser;
    private DataUpdaterInterface $dataUpdater;
    private array $config;
    private bool $isRunning = true;
    private StateRepositoryInterface $repo;

    public function __construct(
        StateRepositoryInterface $repo,
        DataUpdaterInterface $dataUpdater,
        string $configPath = 'config/config.php'
    ) {
        $this->repo = $repo;
        $this->dataUpdater = $dataUpdater;
        $this->config = include $configPath;
        $this->heater = new Heater($repo);
        $this->mashTank = new MashTank($repo);
        $this->condenser = new Condenser($repo);
    }

    public function runCycle(): void
    {
        $sleepSeconds = $this->config['cycleSleepSeconds'];

        while ($this->isRunning) {
            $this->dataUpdater->updateComponents($this->heater, $this->mashTank, $this->condenser);

            $heaterData = $this->heater->getSensorData();

            $this->decideHeating($heaterData['temperature']);

            $this->checkStopConditions($this->mashTank->getSensorData(), $this->condenser->getSensorData());

            $this->saveSystemState();

            if ($this->isRunning) {
                sleep($sleepSeconds);
            }
        }
        $this->heater->setIsOn(false);
        $this->saveSystemState();
        Logger::log('System Stopped!');
    }

    private function decideHeating(float $temperature): void
    {
        if ($temperature < $this->config['heatOnThreshold']) {
            $this->heater->setIsOn(true);
            Logger::log(
                sprintf(
                    'Heating is on (T < %s°C) Temperatue: %s°C',
                    $this->config['heatOnThreshold'],
                    $this->heater->getSensorData()['temperature'] ?? 0
                )
            );
        } elseif ($temperature > $this->config['heatOffThreshold']) {
            $this->heater->setIsOn(false);
            Logger::log(
                sprintf(
                    'Heating is off (T > %s°C) Temperatue: %s°C',
                    $this->config['heatOffThreshold'],
                    $this->heater->getSensorData()['temperature'] ?? 0
                )
            );
        }
    }

    private function checkStopConditions(array $tankData, array $condenserData): void
    {
        if (
            $condenserData['alcoholContent'] < $this->config['stopAlcoholThreshold'] ||
            $tankData['mashLevel'] < $this->config['stopMashThreshold']
        ) {
            $this->isRunning = false;
            $reason = ($condenserData['alcoholContent'] < $this->config['stopAlcoholThreshold']) ?
                sprintf("alcohol < %s", $this->config['stopAlcoholThreshold']) :
                sprintf('level < %s', $this->config['stopMashThreshold']);
            Logger::log('Stop condition: ' . $reason . " %");
        }
    }

    private function saveSystemState(): void
    {
        $state = [
            'isRunning' => $this->isRunning,
            'components' => [
                'heater' => $this->heater->getSensorData(),
                'mashTank' => $this->mashTank->getSensorData(),
                'condenser' => $this->condenser->getSensorData()
            ]
        ];
        $this->repo->save($state);
    }
}
