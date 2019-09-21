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

        $exist = $user->isEmailOrPseudoExist();
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
            $token = "L'adresse email ou le nom d'utilisateur existe déjà";
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

    public function userAbout($request, $id){
        $userArray = $this->verify_token($request);
        if ($userArray==false){
            return new Response(401, ['Content-Type' => 'application/json'], json_encode(array(
                "status" => "error",
                "message" => "Invalid authorized access"
            )));
        }

        $user_id = $userArray["id"];

        if ($id == "self"){
            $id = $user_id;
        }

        $user = new User();
        $user->setId($id);
        $user->load();

        $found = false;
        if ($user->getId()){
            $found = true;

            $userJson = array();
            $userJson['id'] = $user->getId();
            $userJson['pseudo'] = $user->getPseudo();
            $userJson['email'] = $user->getEmail();
            $userJson['date_creation'] = $user->getDate_creation();
            $userJson['photo_profil'] = $user->getPhoto_profil();
            $userJson['bio'] = $user->getBio();
            $userJson['actif'] = $user->getActif();
            $userJson['date_last_modification'] = $user->getDate_last_modification();

            $is_current_user = $user_id == $user->getId() ? true : false;

            return $this->renderJson(array(
                "found_user" => $found,
                "is_current_user" => $is_current_user,
                "user" => $userJson,
            ));
        }
        else {
            return $this->renderJson(array(
                "found_user" => $found,
            ));
        }
    }

    public function search($request){
        $userArray = $this->verify_token($request);
        if ($userArray==false){
            return new Response(401, ['Content-Type' => 'application/json'], json_encode(array(
                "status" => "error",
                "message" => "Invalid authorized access"
            )));
        }

        $user_id = $userArray["id"];

        $user = new User();
        $search = $this->getRequestBody($request,'search');
        $scope = $this->getRequestBody($request, 'scope');

        $result = array();
        if ($scope == 'global'){
            $result = $user->findSearch($search);
        }
        else if ($scope == 'contact'){
            $the_user = $this->getRequestBody($request, 'user_id');
            if ($the_user == "self"){
                $the_user = $user_id;
            }
            $result = $user->findSearchInContact($search, $the_user);
        }
        

        $usersJson = array();
        foreach ($result as $u) {
            $uArr = array();
            $uArr["is_current_user"] = $u->getId() == $user_id ? true : false;
            $uArr["id"] = $u->getId();
            $uArr["pseudo"] = $u->getPseudo();
            $uArr["photo_profil"] = $u->getPhoto_profil();
            $uArr["bio"] = $u->getBio();
            $uArr["actif"] = $u->getActif();
            $usersJson[] = $uArr;
        }

        return $this->renderJson(array(
            "nb" => count($usersJson),
            "users" => $usersJson,
        ));
    }

    public function addUser($request){
        $userArray = $this->verify_token($request);
        if ($userArray==false){
            return new Response(401, ['Content-Type' => 'application/json'], json_encode(array(
                "status" => "error",
                "message" => "Invalid authorized access"
            )));
        }

        $user_id = $userArray["id"];

        $the_user_to_request = $this->getRequestBody($request, 'user_id');

        $user = new User();
        $result = $user->addRequest($user_id, $the_user_to_request);

        return $this->renderJson(array(
            "status" => $result["status"],
            "message" => $result["message"],
        ));
    }

    public function allUsersFromRequest($request){
        $userArray = $this->verify_token($request);
        if ($userArray==false){
            return new Response(401, ['Content-Type' => 'application/json'], json_encode(array(
                "status" => "error",
                "message" => "Invalid authorized access"
            )));
        }

        $user_id = $userArray["id"];

        $user = new User();
        $user->setId($user_id);

        $users = $user->getAllUsersFromRequest($array=true);

        return $this->renderJson(array(
            "nb" => count($users),
            "users" => $users,
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
