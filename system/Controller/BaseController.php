<?php

namespace Framework\Controller;

use Framework\Renderer\RendererInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'X-Requested-With, Content-Type, Accept, Origin, Authorization',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, PATCH, OPTIONS'
        ],json_encode($data));
    }

    public function getRequestBody(ServerRequestInterface $request, string $key)
    {
        if(isset($request->getParsedBody()[$key])){
            return $request->getParsedBody()[$key];
        }
        else{
            return $request->getQueryParams()[$key];
        }
    }

    public function getUploadedFiles(ServerRequestInterface $request, string $key)
    {        
        if (isset($request->getUploadedFiles()[$key])){
            return $request->getUploadedFiles()[$key];
        }
        else{
            return null;
        }
    }

}
