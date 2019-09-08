<?php

use GuzzleHttp\Psr7\Response;

$router->get('/blog','BlogController@index');
 $router->get('/article',function($request){
     return new Response(200, [], '<h1>Bienvenue sur le article</h1>');
 });
 $router->get('/article/{id}',function($request,$id){
     return new Response(200, [], '<h1>Bienvenue sur le article'.$id.'</h1>');
 });