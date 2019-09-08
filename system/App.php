<?php
namespace Framework;

use DI\Container;
use Framework\Router\Router;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App
{
    private $container;
    private $router;

    public function __construct(Container $container)
    {
        $this->container = $container;
        
    }

    public function run(ServerRequestInterface $request):ResponseInterface
    {
        
        $this->router = $this->container->get(Router::class);
        $this->router->setNamespace($this->container->get('app.controller'));
        $this->router->getAppRoutes($this->router,$this->container->get('app.router'));
        $this->router->set404(function() {
            return new Response(404, [], '<h1>Page Not found</h1>');
        });
        $response = $this->router->run($request);
        return $response;
    }
}
