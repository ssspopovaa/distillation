<?php

require 'vendor/autoload.php';

use App\Services\DistillerController;
use App\Factories\RepositoryFactory;
use App\Factories\DataUpdaterFactory;

$config = include 'config/config.php';
$repo = RepositoryFactory::create($config);
$updater = DataUpdaterFactory::create($config);
$controller = new DistillerController($repo, $updater);
$controller->runCycle();
