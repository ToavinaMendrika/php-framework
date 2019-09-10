<?php
namespace Framework\Renderer;

class PHPRenderer implements RendererInterface
{
    const DEFAULT_NAMESPACE = '__MAIN';

    private $paths = [];
    private $globals = [];

    public function __construct(?string $defaultpath = null)
    {
        $this->addPath($defaultpath);
    }
    
    /**
     * Ajout chement dans les tableau des routes de l'application
     *
     * @param string $namespace
     * @param string|null $path
     * @return void
     */
    public function addPath(string $namespace, ?string $path = null):void
    {
        if (is_null($path)) {
            $this->paths[self::DEFAULT_NAMESPACE] = str_replace('/', DIRECTORY_SEPARATOR, $namespace);
        } else {
            $this->paths[$namespace] = str_replace('/', DIRECTORY_SEPARATOR, $path);
        }
    }

    /**
     * Rendre un vue,
     * si le namespace de la vue n'est pas definie, inclue defaut __MAIN (chemin des layout)
     *
     * @param string $view
     * @param array $variable
     * @return string
     */
    public function render(string $view, array $variable = []):string
    {
        if ($this->hasNameSpace($view)) {
            $namespace = substr($view, 1, strpos($view, '/')-1);
            $path = str_replace('/', DIRECTORY_SEPARATOR, $this->paths[$namespace] . substr($view, strpos($view, '/')) . '.php');
        } else {
            $path = $this->paths[self::DEFAULT_NAMESPACE] . DIRECTORY_SEPARATOR . $view . '.php';
        }
        ob_start();
        extract($this->globals);
        $renderer = $this;
        extract($variable);
        require($path);
        return ob_get_clean();
    }

    /**
     *  Ajout variable globale dans tous les vues
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function addGlobal(string $key, $value):void
    {
        $this->globals[$key] = $value;
    }

    private function hasNameSpace(string $view):bool
    {
        return $view[0] === '@';
    }
}
