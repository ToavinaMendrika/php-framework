<?php

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$router->get('/blog','BlogController@index');
// $router->get('/user/register','UserController@register');
$router->get('/blog/{id}/{slug}','BlogController@show');

$router->before('GET|POST', '/blog/.*', function($request) {
    if(isset($request->getHeader('Authorization')[0])){
        if($request->getHeader('Authorization')[0] === 'key'){
            return new Response();          
        }
        else {
            
        }
    }
    else {     
        exit('Unothorized resource');
    }

});

$router->post('/blog/create','BlogController@create');

$router->get('/article',function(Request $request){
    return new Response(200, [], '<h1>Bienvenue sur le article</h1>');
});
$router->get('/article/{id}',function(Request $request,int $id){
    return new Response(200, [], '<h1>Bienvenue sur le article'.$id.'</h1>');
});
$router->set404(function() {
    return new Response(404, [], '<h1>Page Not found</h1>');
});

$router->post('/user/register', 'UserController@register');
$router->post('/user/login', 'UserController@login');