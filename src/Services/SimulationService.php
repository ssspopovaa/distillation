<?php

namespace App\Services;

use App\Components\Heater;
use App\Components\MashTank;
use App\Components\Condenser;
use Exception;

class SimulationService implements DataUpdaterInterface
{
    private array $data;
    private int $index = 0;

    public function __construct(string $filename = 'config/sim_data.json')
    {
        $this->data = json_decode(file_get_contents($filename), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid sim data file');
        }
    }

    public function updateComponents(Heater $heater, MashTank $mashTank, Condenser $condenser): void
    {
        if ($this->index >= count($this->data)) {
            $this->index = 0;
        }
        $stepData = $this->data[$this->index++];

        $heaterData = $stepData['heater'] ?? [];
        $heater->setTemperature($heaterData['temperature'] ?? $heater->getSensorData()['temperature']);
        $heater->setIsOn($heaterData['isOn'] ?? false);

        $tankData = $stepData['mashTank'] ?? [];
        $mashTank->setMashLevel($tankData['mashLevel'] ?? $mashTank->getSensorData()['mashLevel']);
        $condenser->setAlcoholContent(
            $tankData['alcoholContent'] ?? $condenser->getSensorData()['alcoholContent']
        );

        $condData = $stepData['condenser'] ?? [];
        $condenser
            ->setCollectedVolume($condData['collectedVolume'] ?? $condenser->getSensorData()['collectedVolume']);
    }
}
