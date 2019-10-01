<?php

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$router->get('/','PageController@index');
$router->get('/blog','BlogController@index');

$router->set404(function() {
    return new Response(404, [], '<h1>Page Not found</h1>');
});

/*
* username: the user pseudo
* email: the user email
* pass: the user password
*/
$router->post('/user/register', 'UserController@register');

/*
* email: the user email
* pass: the user password
*/
$router->post('/user/login', 'UserController@login');

/*
* Authorization: jwt
*/
$router->get('/chat/discussion', 'DiscussionController@listDiscussion');

/*
* Authorization: jwt
*/
$router->get('/chat/discussion/{id}', 'DiscussionController@discussion');

/*
* Authorization: jwt
* message: the message to send
* type: "text"/"media"
*/
$router->post('/chat/discussion/{id}', 'DiscussionController@sendMessage');

/*
* Authorization: jwt
*/
$router->get('/user/{id}', 'UserController@userAbout');	// '/user/self' correspond à l'user courant

/*
* Authorization: jwt
* search: letters/words for searching user by pseudo or email
* scope: "global"/"contact"
*/
$router->post('/user/search', 'UserController@search');

/*
* Authorization: jwt
* user_id: user id who will receive the request
*/
$router->post('/user/request/add', 'UserController@addUser');

/*
* Authorization: jwt
*/
$router->post('/user/request/all', 'UserController@allUsersFromRequest');

/*
* Authorization: jwt
*/
$router->post('/user/request/seen', 'UserController@seeRequests');

/*
* Authorization: jwt
* response: "accept"/"refuse"
* send_id: user id who sent the request
*/
$router->post('/user/request/response', 'UserController@responseRequest');

/*
* Authorization: jwt
* discussion_id: discussion id where all messages will be seen by the current user 
*/
$router->post('/chat/message/seen', 'MessageController@seeMessages');

/*
* Authorization: jwt
* user_id: user id that the current user will send a message to 
* message: the message to send
* type: "text"/"media"
*/
$router->post('/chat/discussion_profil', 'DiscussionController@sendMessageFromProfil');