<?php
namespace App\Controllers;

use Framework\Controller\BaseController;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repositories\UserRepository as User;
use Firebase\JWT\JWT;

class UserController extends BaseController
{
    private $key = "example";

    public function register(Request $request){
        $post = $request->getQueryParams();

        $user = new User();
        $user->setEmail($post['email']);
        $user->setPseudo($post['username']);
        $user->setPassword($post['pass']);
        $user->setActif(true);

        $exist = $user->isEmailExists();
        $status = "error";
        $token = "";

        if (!$exist){
            $user = $user->create();

            $id = $user->getId();
            $userData = array(
                "id" => $id,
            );
            $key = $this->key;
            $jwt = $this->getEncodeJwt($userData, $key);
            $user->setToken($jwt);
            $user->update();

            $status = "success";
            $token = $jwt;
        }
        else {
            $status = "error";
            $token = "L'adresse email existe déjà";
        }

        return $this->renderJson(array(
            "status" => $status,
            "token" => $token,
        ));
    }

    public function login(Request $request){
        $post = $request->getQueryParams();

        $user = new User();
        $user->setEmail($post['email']);
        $user->setPassword($post['pass']);

        $exist = $user->isUserExists();
        $status = "error";
        $token = "";

        if ($exist){
            $user = $user->loadByEmail();

            $id = $user->getId();
            $userData = array(
                "id" => $id,
            );
            $key = $this->key;
            $jwt = $this->getEncodeJwt($userData, $key);
            $user->setToken($jwt);
            $user->update();

            $status = "success";
            $token = $jwt;
        }
        else {
            $status = "error";
            $token = "L'adresse email ou le mot de passe est invalide";
        }

        return $this->renderJson(array(
            "status" => $status,
            "token" => $token,
        ));
    }

    public function getEncodeJwt($data, $key){
        // $tokenId    = base64_encode(mcrypt_create_iv(32));
        $issuedAt   = time();
        $notBefore  = $issuedAt + 10;             //Adding 10 seconds
        $expire     = $notBefore + 60;            // Adding 60 seconds
        $serverName = $_SERVER['SERVER_NAME'];

        $token = array(
            // 'iat'  => $issuedAt,         // Issued at: time when the token was generated
            // 'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
            'iss'  => $serverName,       // Issuer
            // 'nbf'  => $notBefore,        // Not before
            "iat" => 1356999524,
            "nbf" => 1357000000,
            // 'exp'  => $expire,           // Expire
            'data' => $data
        );

        $jwt = JWT::encode($token, $key);
        return $jwt;
    }

    public function getDecodeJwt($jwt, $key){
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        return $decoded;
    }
}
