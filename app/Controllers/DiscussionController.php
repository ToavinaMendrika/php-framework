<?php
namespace App\Controllers;

use Framework\Controller\BaseController;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repositories\DiscussionRepository as Discussion;
use Firebase\JWT\JWT;

class DiscussionController extends BaseController
{
    private $key = "example";

    protected function verify_token($request)
    {
        $header = $request->getQueryParams();
        $token = $header['Authorization'][0];
        // $header = $this->input->get_request_header('Authorization', TRUE);
        try{
            $current_user = $this->getDecodeJwt($token, $this->key);
            return (array)$current_user->data;
        }
        catch (\Exception $e){
            // $this->response(array(
            //     'status' => 'error',
            //     'message' => 'Token invalid'
            // ));         

            echo "Token invalid";

            die();
        }
    }

    public function listDiscussion($request){
        $userArray = $this->verify_token($request);
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
            $discussionJson["date_last_message"] = $discussion->getDate_last_message();
            $discussionsJson[] = $discussionJson;
        }

        return $this->renderJson(
            $discussionsJson
        );
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
