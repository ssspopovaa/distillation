<?php

namespace App\Factories;

use App\Services\DataUpdaterInterface;
use App\Services\SimulationService;
use App\Services\RealDataService;

class DataUpdaterFactory
{
    public static function create(array $config): DataUpdaterInterface
    {
        $mode = $config['simulationMode'] ?? true;
        $cfg = $config['updaterConfig'] ?? [];

        if ($mode) {
            return new SimulationService($cfg['simDataFile'] ?? 'config/sim_data.json');
        } else {
            return new RealDataService();
        }
    }
}
