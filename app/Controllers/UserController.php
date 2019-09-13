<?php
namespace App\Controllers;

use Framework\Controller\BaseController;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repositories\UserRepository as User;
use Firebase\JWT\JWT;
use GuzzleHttp\Psr7\Response;

class UserController extends BaseController
{

    protected function verify_token($request)
    {
        $header = $request->getHeaders();
        $token = $header['Authorization'][0];
        try{
            $current_user = $this->getDecodeJwt($token, getenv("APP_KEY"));
            return (array)$current_user->data;
        }
        catch (\Exception $e){
            return false;
            
        }
    }

    public function register(Request $request){

        $user = new User();
        $user->setEmail($this->getRequestBody($request, 'email'));
        $user->setPseudo($this->getRequestBody($request, 'username'));
        $user->setPassword($this->getRequestBody($request, 'pass'));
        $user->setActif(true);

        $exist = $user->isEmailExists();
        $status = "error";
        $token = "";

        if (!$exist){
            $user->create();

            $id = $user->getId();
            $userData = array(
                "id" => $id,
            );
            $jwt = $this->getEncodeJwt($userData, getenv('APP_KEY'));

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
        $user = new User();
        $user->setEmail($this->getRequestBody($request,'email'));
        $user->setPassword($this->getRequestBody($request,'pass'));

        $exist = $user->isUserExists();
        $status = "error";
        $token = "";

        if ($exist){
            $user->loadByEmail();

            $id = $user->getId();
            $userData = array(
                "id" => $id,
            );

            $jwt = $this->getEncodeJwt($userData, getenv('APP_KEY'));
            
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
