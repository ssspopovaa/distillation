<?php

require 'vendor/autoload.php';

use App\Services\MonitoringService;
use App\Factories\RepositoryFactory;

$config = include 'config/config.php';
$repo = RepositoryFactory::create($config);
$monitor = new MonitoringService($repo);
$monitor->start();
