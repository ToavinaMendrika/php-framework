<?php

namespace Framework\Controller;

use Framework\Renderer\RendererInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

class BaseController
{

    protected $container;
    protected $renderer;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->renderer = $this->container->get(RendererInterface::class);
    }

    public function render(string $view,array $variables = []) :ResponseInterface
    {
        return new Response(200, [], $this->renderer->render($view, $variables));
    }

    public function renderJson(array $data):ResponseInterface
    {
        return new Response(200,[
            'Content-Type' => 'application/json'
        ],json_encode($data));
    }

}
