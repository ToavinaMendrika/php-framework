<?php
namespace App\Controllers;

use Framework\Controller\BaseController;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repositories\DiscussionRepository as Discussion;
use Firebase\JWT\JWT;
use GuzzleHttp\Psr7\Response;

class DiscussionController extends BaseController
{
    private $key = "example";

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

    public function listDiscussion($request){
        $userArray = $this->verify_token($request);
        if ($userArray==false){
            return new Response(401, ['Content-Type' => 'application/json'], json_encode(array(
                "status" => "error",
                "message" => "Invalid authorized access"
            )));
        }

        $id = $userArray["id"];

        $discussion = new Discussion();

        $discussionsArray = $discussion->getDiscussionsFromUserId($id);

        $discussionsJson = array();

        foreach ($discussionsArray as $discussion) {
            $discussionJson = array();
            $discussionJson["id"] = $discussion->getId();
            $discussionJson["name"] = $discussion->getName();
            $discussionJson["users"] = $discussion->getUsersNecessityArray();
            $discussionJson["photo_profil"] = $discussion->getPhoto_profil();
            $discussionJson["notseen"] = $discussion->getNotSeenMessages($id);
            $discussionJson["date_last_message"] = $discussion->getDate_last_message();
            $discussionsJson[] = $discussionJson;
        }

        return $this->renderJson(
            $discussionsJson
        );
    }

    public function discussion($request){

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
