<?php
define('ROOT_DIRECTORY',dirname(__DIR__) . DIRECTORY_SEPARATOR);
require ROOT_DIRECTORY  . 'vendor' .DIRECTORY_SEPARATOR.'autoload.php';

use DI\ContainerBuilder;
use Framework\App;
use GuzzleHttp\Psr7\ServerRequest;
$whoops = new \Whoops\Run;
$whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$builder = new ContainerBuilder();
$builder->useAutowiring(true);
$builder->addDefinitions(ROOT_DIRECTORY . DIRECTORY_SEPARATOR . 'system' .DIRECTORY_SEPARATOR . 'config.php');
$container = $builder->build();


$app = new App($container);
$response = $app->run(ServerRequest::fromGlobals());
\Http\Response\send($response);