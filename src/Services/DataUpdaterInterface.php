<?php

namespace App\Services;

use App\Components\Heater;
use App\Components\MashTank;
use App\Components\Condenser;

interface DataUpdaterInterface
{
    public function updateComponents(
        Heater $heater,
        MashTank $mashTank,
        Condenser $condenser
    ): void;
}
