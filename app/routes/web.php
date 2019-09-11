<?php

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$router->get('/blog','BlogController@index');
$router->post('/user/register', 'UserController@register');
$router->post('/user/login', 'UserController@login');
