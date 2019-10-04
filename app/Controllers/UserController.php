<?php
namespace App\Controllers;

use Framework\Controller\BaseController;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repositories\UserRepository as User;
use Firebase\JWT\JWT;
use GuzzleHttp\Psr7\Response;

/**
* Description of UserController
*
* @author David Rambolajaona <david.rambolajaon@esti.mg>
*/
class UserController extends BaseController
{
    /**
    * Verify if a token exists in the header
    *
    * @param object $request
    * @return array|bool
    */
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

    /**
    * Register a new user
    *
    * @api
    * @param object $request
    * @return array
    */
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

    /**
    * Login of an user
    *
    * @api
    * @param object $request
    * @return array
    */
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

    /**
    * Information about an user
    *
    * @api
    * @param object $request
    * @param int|string $id
    * @return array
    */
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
            $userJson['is_friend'] = $user->isFriendOf($user_id);
            $userJson['id'] = $user->getId();
            $userJson['pseudo'] = $user->getPseudo();
            $userJson['email'] = $user->getEmail();
            $userJson['date_creation'] = $user->getDate_creation();
            $userJson['photo_profil'] = $user->getPhoto_profil();
            $userJson['photo_info'] = $user->getPhotoInfo($user->getPhoto_profil());
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

    /**
    * List of users that are result of the search
    *
    * @api
    * @param object $request
    * @return array
    */
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
            $is_friend = $u->isFriendOf($user_id);
            $uArr["is_current_user"] = $u->getId() == $user_id ? true : false;
            $uArr["is_friend"] = $is_friend;
            $uArr["id"] = $u->getId();
            $uArr["pseudo"] = $u->getPseudo();
            $uArr["email"] = $u->getEmail();
            $uArr["photo_profil"] = $u->getPhoto_profil();
            $uArr["photo_info"] = $u->getPhotoInfo($u->getPhoto_profil());
            $uArr["bio"] = $u->getBio();
            $uArr["actif"] = $u->getActif();
            $usersJson[] = $uArr;
        }

        return $this->renderJson(array(
            "nb" => count($usersJson),
            "users" => $usersJson,
        ));
    }

    /**
    * Sending a friend request to an user or deleting the user from the contact
    *
    * @api
    * @param object $request
    * @return array
    */
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
        $result = $user->sendRequest($user_id, $the_user_to_request);

        return $this->renderJson(array(
            "status" => $result["status"],
            "message" => $result["message"],
        ));
    }

    /**
    * List of users who sent a request to the current user
    *
    * @api
    * @param object $request
    * @return array
    */
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
        $nb_not_seen = $user->getNbNotSeen();

        return $this->renderJson(array(
            "nb_users" => count($users),
            "nb_not_seen" => $nb_not_seen,
            "users" => $users,
        ));
    }

    /**
    * Make the requests seen by the current user
    *
    * @api
    * @param object $request
    * @return array
    */
    public function seeRequests($request){
        $userArray = $this->verify_token($request);
        if ($userArray==false){
            return new Response(401, ['Content-Type' => 'application/json'], json_encode(array(
                "status" => "error",
                "message" => "Invalid authorized access"
            )));
        }

        $user_id = $userArray["id"];

        $status = "success";
        $message = "";

        $user = new User();
        $user->setId($user_id);
        $count = $user->seeRequests();
        $message = (string)$count . " demandes sont vues";

        return $this->renderJson(array(
            "status" => $status,
            "message" => $message,
        ));
    }

    /**
    * Accepting or refusing a request by the current user
    *
    * @api
    * @param object $request
    * @return array
    */
    public function responseRequest($request){
        $userArray = $this->verify_token($request);
        if ($userArray==false){
            return new Response(401, ['Content-Type' => 'application/json'], json_encode(array(
                "status" => "error",
                "message" => "Invalid authorized access"
            )));
        }

        $user_id = $userArray["id"];

        $status = "error";
        $message = "";

        $user = new User();
        $user->setId($user_id);
        $response = $this->getRequestBody($request, 'response');
        $send_id = $this->getRequestBody($request, 'send_id');
        if ($response == "accept"){
            $is_ok = $user->responseOfRequest($send_id, $accept=true);
            if ($is_ok){
                $status = "success";
                $message = "Acceptation de la demande";
            }
        }
        // $response == "refuse"
        else{
            $is_ok = $user->responseOfRequest($send_id, $accept=false);
            if ($is_ok){
                $status = "success";
                $message = "Refus de la demande";
            }
        }

        return $this->renderJson(array(
            "status" => $status,
            "message" => $message,
        ));
    }

    /**
    * Updating the information of the current user like pseudo, password, bio
    *
    * @api
    * @param object $request
    * @return array
    */
    public function editUser($request){
        $userArray = $this->verify_token($request);
        if ($userArray==false){
            return new Response(401, ['Content-Type' => 'application/json'], json_encode(array(
                "status" => "error",
                "message" => "Invalid authorized access"
            )));
        }

        $user_id = $userArray["id"];

        $status = "error";
        $lastValue = "";
        $newValue = "";

        $toEdit = $this->getRequestBody($request, 'toEdit');
        $value = $this->getRequestBody($request, 'value');

        $user = new User();
        $user->setId($user_id);
        $user->load();

        switch ($toEdit) {
            case 'pseudo':
                if (isset($value) AND $value != ""){
                    $lastValue = $user->getPseudo();
                    $user->setPseudo($value);
                    $newValue = $user->getPseudo();
                    $user->update();
                    $status = "success";
                }
                break;
            case 'password':
                if (isset($value) AND $value != ""){
                    $lastValue = $user->getPassword();
                    $user->setPassword($value);
                    $newValue = $user->getPassword();
                    $user->update();
                    $status = "success";
                }
                break;
            case 'bio':
                if (isset($value) AND $value != ""){
                    $lastValue = $user->getBio();
                    $user->setBio($value);
                    $newValue = $user->getBio();
                    $user->update();
                    $status = "success";
                }
                break;
            default:
                break;
        }

        return $this->renderJson(array(
            "status" => $status,
            "to_edit" => $toEdit,
            "last_value" => $lastValue,
            "new_value" => $newValue,
        ));
    }

    /**
    * Verify if the current user's password matches with the sent one
    *
    * @api
    * @param object $request
    * @return array
    */
    public function verifyPassword($request){
        $userArray = $this->verify_token($request);
        if ($userArray==false){
            return new Response(401, ['Content-Type' => 'application/json'], json_encode(array(
                "status" => "error",
                "message" => "Invalid authorized access"
            )));
        }

        $user_id = $userArray["id"];
        $password = $this->getRequestBody($request, 'password');

        $user = new User();
        $user->setId($user_id);
        $user->setPassword($password);
        $is_password_true = $user->isPasswordTrue();

        return $this->renderJson(array(
            "is_password_true" => $is_password_true,
        ));
    }

    /**
    * Update the current user's profil picture
    *
    * @api
    * @param object $request
    * @return array
    */
    public function editUserPhoto($request){
        $userArray = $this->verify_token($request);
        if ($userArray==false){
            return new Response(401, ['Content-Type' => 'application/json'], json_encode(array(
                "status" => "error",
                "message" => "Invalid authorized access"
            )));
        }

        $user_id = $userArray["id"];

        $status = "error";
        $message = "";
        $lastValue = array();
        $newValue = array();

        $maxSize = 1000000; //octet

        $photo = $this->getUploadedFiles($request, 'photo');

        if ($photo AND $photo->getError() == 0){
            if ($photo->getSize() <= $maxSize){
                $infosFichier = pathinfo($photo->getClientFileName());
                $extension = $infosFichier['extension'];
                $extensionsAuorisees = array('jpg','jpeg','gif','png');
                if (in_array($extension, $extensionsAuorisees)){
                    $newFileName = $user_id . date('YmdHis') . '.' . $extension;
                    $destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/simple-chat/public/images/user/';
                    // move_uploaded_file($photo->getFile(), $destinationPath.$newFileName);
                    $photo->moveTo($destinationPath.$newFileName);

                    $user = new User();
                    $user->setId($user_id);
                    $user->load();
                    $lastIdPhoto = $user->getPhoto_profil();
                    $lastValue = $user->getPhotoInfo($lastIdPhoto);
                    $id_photo = $user->updatePhotoProfil($newFileName);
                    $user->setPhoto_profil($id_photo);
                    $user->update();
                    $newIdPhoto = $user->getPhoto_profil();
                    $newValue = $user->getPhotoInfo($newIdPhoto);

                    $status = "success";
                }
                else{
                    $message = "Extension non autorisée";
                }
            }
            else{
                $message = "Max taille fichier : " . (string)$maxSize . " (octet)";
            }
        }
        else {
            if ($photo){
                $message = "Error code : " . (string)$photo->getError();
            }
            else{
                $message = "Fichier inexistant";
            }
        }

        return $this->renderJson(array(
            "status" => $status,
            "message" => $message,
            "last_value" => $lastValue,
            "new_value" => $newValue,
        ));
    }

    /**
    * List of the user's contact
    *
    * @api
    * @param object $request
    * @return array
    */
    public function listContact($request){
        $userArray = $this->verify_token($request);
        if ($userArray==false){
            return new Response(401, ['Content-Type' => 'application/json'], json_encode(array(
                "status" => "error",
                "message" => "Invalid authorized access"
            )));
        }

        $user_id = $userArray["id"];

        $user_id_contact_list = $id;
        $user_id_contact_list = $this->getRequestBody($request, 'user_id');
        if ($user_id_contact_list == "self"){
            $user_id_contact_list = $user_id;
        }

        $status = "success";
        $listContact = array();
        $nb = 0;

        $user = new User();
        $listContact = $user->getListContact($user_id_contact_list);
        $nb = $user->getNbFriends($user_id_contact_list);

        return $this->renderJson(array(
            "status" => $status,
            "nb" => $nb,
            "list_contact" => $listContact,
        ));
    }

    /**
    * Getting an encoded string jwt
    *
    * @param array $data
    * @param string $key
    * @return string
    */
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

    /**
    * Getting the decoded object of a jwt string
    *
    * @param string $jwt
    * @param string $key
    * @return object
    */
    public function getDecodeJwt($jwt, $key){
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        return $decoded;
    }
}
