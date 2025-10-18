<?php

namespace App\Services;

use App\Components\Heater;
use App\Components\MashTank;
use App\Components\Condenser;

class RealDataService implements DataUpdaterInterface
{
    public function updateComponents(Heater $heater, MashTank $mashTank, Condenser $condenser): void
    {
        // TODO connect to real sensors
    }
}
