<?php
namespace Framework\Controller;

use Framework\Renderer\RendererInterface;
use Psr\Container\ContainerInterface;

class BaseControllerFactory
{
    public function __invoke(ContainerInterface $container): BaseController
    {
        return new BaseController($container->get(RendererInterface::class));
    }
}
