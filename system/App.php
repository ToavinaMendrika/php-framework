<?php
namespace Framework;

use Framework\Controller\BaseController;
use Framework\Renderer\RendererInterface;
use Framework\Router\Router;
use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App
{
    private $container;
    private $router;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;      
    }

    public function run(ServerRequestInterface $request):ResponseInterface
    {
        $this->router = $this->container->get(Router::class);
        $this->router->setNamespace($this->container->get('app.controller'));
        $this->router->getAppRoutes($this->router,$this->container->get('app.router'));
        
        $response = $this->router->run($request, $this->container);
        return $response;
    }
}
