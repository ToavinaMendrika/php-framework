<?php

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$router->get('/','PageController@index');
$router->get('/blog','BlogController@index');

$router->set404(function() {
    return new Response(404, [], '<h1>Page Not found</h1>');
});

$router->post('/user/register', 'UserController@register');
$router->post('/user/login', 'UserController@login');
$router->get('/chat/discussion', 'DiscussionController@listDiscussion');
$router->get('/chat/discussion/{id}', 'DiscussionController@discussion');