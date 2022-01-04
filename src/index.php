<?php

declare(strict_types = 1);

use Sonro\Checkup\Infrastructure\Container\ServiceContainer;

require __DIR__.'/bootstrap.php';

$container = new ServiceContainer();
$app = $container->getApplication();
$app->run($argv);