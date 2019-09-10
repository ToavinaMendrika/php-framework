<?php
namespace Framework\Renderer;

interface RendererInterface
{
     /**
     * Ajout chement dans les tableau des routes de l'application
     *
     * @param string $namespace
     * @param string|null $path
     * @return void
     */
    public function addPath(string $namespace, ?string $path = null):void;
    
    /**
     * Rendre un vue,
     * si le namespace de la vue n'est pas definie, inclue defaut __MAIN (chemin des layout)
     *
     * @param string $view
     * @param array $variable
     * @return string
     */
    public function render(string $view, array $variable = []):string;

    /**
     *  Ajout variable globale dans tous les vues
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function addGlobal(string $key, $value):void;
}
