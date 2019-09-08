<?php

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

$router->get('/blog','BlogController@index');

 $router->get('/article',function(ServerRequestInterface $request){
     return new Response(200, [], '<h1>Bienvenue sur le article</h1>');
 });
 $router->get('/article/{id}',function(ServerRequestInterface $request,int $id){
     return new Response(200, [], '<h1>Bienvenue sur le article'.$id.'</h1>');
 });