<?php
namespace App\Controllers;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BlogController
{
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(200, [], '<h1>Bienvenue sur le blog</h1>');
    }
}
