<?php
namespace Framework\Renderer;

use \Twig_Environment;
use \Twig_Loader_Filesystem;

class TwigRenderer implements RendererInterface
{
   
    private $loader;
    private $twig;

    public function __construct(string $path)
    {
        $this->loader = new Twig_Loader_Filesystem($path);
        $this->twig =  new Twig_Environment($this->loader, []);
    }
    

    public function addPath(string $namespace, ?string $path = null):void
    {
        $this->loader->addPath($path, $namespace);
    }


    public function render(string $view, array $variable = []):string
    {
        return $this->twig->render($view . '.html.twig', $variable);
    }


    public function addGlobal(string $key, $value):void
    {
        $this->twig->addGlobal($key, $value);
    }
}
