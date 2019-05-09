<?php

use App\Application;
use App\Config;
use App\Container;
use JMS\Serializer\SerializerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use GuzzleHttp\Client;
use Doctrine\Common\Annotations\AnnotationRegistry;

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/settings_inc.php';

AnnotationRegistry::registerLoader('class_exists');
$appSerializer = SerializerBuilder::create()->build();
$appConfig = Config::loadFromFile(APP_CONFIG_FILE_NAME, $appSerializer);
$appLogger = new Logger('checkup_logger');
$appLogger->pushHandler(new StreamHandler(APP_LOG_FILE_NAME, Logger::INFO));
$appClient = new Client(['timeout' => 2.0]);

$container = new Container(
    $appSerializer,
    $appConfig,
    $appLogger,
    $appClient
);

$application = new Application($container);
$application->run();
