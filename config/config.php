<?php

return [
    // Heating conditions
    'heatOnThreshold' => 70.0,
    'heatOffThreshold' => 85.0,

    // Terms of suspension
    'stopAlcoholThreshold' => 30.0,
    'stopMashThreshold' => 10.0,

    // Loop
    'cycleSleepSeconds' => 5,

    // State repository
    'storageType' => 'redis',
    'storageConfig' => [
        'redis' => ['host' => 'redis', 'port' => 6379],
        'file' => ['baseDir' => 'state/'],
    ],

    // Simulation mode
    'simulationMode' => true,
    'updaterConfig' => [
        'simDataFile' => __DIR__ . '/sim_data.json',
    ],

    'monitoringInterval' => 5,
];
