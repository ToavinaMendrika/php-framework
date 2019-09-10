<?php

use Framework\Controller\BaseController;
use Framework\Controller\BaseControllerFactory;
use Framework\Renderer\RendererInterface;
use Framework\Renderer\TwigRendererFactory;

return [
    'db.host' => 'localhost',
    'db.port' => 3336,
    'app.controller' => '\App\Controllers',
    'app.router' => str_replace('/',DIRECTORY_SEPARATOR, ROOT_DIRECTORY.'app/routes/web.php'),
    'app.view' => str_replace('/',DIRECTORY_SEPARATOR, ROOT_DIRECTORY.'app/views/'),
    
    RendererInterface::class => \DI\factory(TwigRendererFactory::class),
    BaseController::class => \DI\factory(BaseControllerFactory::class)

];