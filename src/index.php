<?php

declare(strict_types = 1);

use Sonro\Checkup\Infrastructure\Container\ServiceContainer;

require __DIR__.'/bootstrap.php';

$container = new ServiceContainer();
$app = $container->getApplication();
$args = array_slice($argv, 1);
$app->runWithArgs($args);