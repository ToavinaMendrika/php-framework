<?php
namespace App\Controllers;

use Framework\Controller\BaseController;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repositories\DiscussionRepository as Discussion;
use App\Repositories\MessageRepository as Message;
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

        $user_id = $userArray["id"];

        $discussion = new Discussion();

        $discussionsArray = $discussion->getDiscussionsFromUserId($user_id);

        $discussionsJson = array();

        foreach ($discussionsArray as $discussion) {
            $discussionJson = array();
            $discussionJson["id"] = $discussion->getId();
            $discussionJson["type"] = $discussion->getType();
            $discussionJson["name"] = $discussion->getName();
            $discussionJson["users"] = $discussion->getUsersNecessityArray();
            $discussionJson["photo_profil"] = $discussion->getPhoto_profil();
            $discussionJson["notseen"] = $discussion->getNotSeenMessages($user_id);
            $discussionJson["last_message"] = $discussion->getLast_messageArray();
            $discussionsJson[] = $discussionJson;
        }

        return $this->renderJson(array(
            "current_user_id" => $user_id,
            "discussions" => $discussionsJson,
        ));
    }

    public function discussion($request, $discu_id){
        $userArray = $this->verify_token($request);
        if ($userArray==false){
            return new Response(401, ['Content-Type' => 'application/json'], json_encode(array(
                "status" => "error",
                "message" => "Invalid authorized access"
            )));
        }

        $user_id = $userArray["id"];

        $discussion = new Discussion();
        $discussion->setId($discu_id);

        $messages = $discussion->getMessages();

        $messagesJson = array();
        foreach ($messages as $message) {
            $messageArray = array();
            $messageArray["type"] = $message->getType();
            $messageArray["msg_text"] = $message->getMsg_text();
            $messageArray["date"] = $message->getDate_envoi();
            $messageArray["user"] = $message->getUserNecessityArray();
            array_push($messagesJson, $messageArray);
        }

        return $this->renderJson(array(
            "current_user_id" => $user_id,
            "messages" => $messagesJson,
        ));
    }

    public function sendMessage($request, $discu_id){
        $userArray = $this->verify_token($request);
        if ($userArray==false){
            return new Response(401, ['Content-Type' => 'application/json'], json_encode(array(
                "status" => "error",
                "message" => "Invalid authorized access"
            )));
        }

        $user_id = $userArray["id"];
        $message_text = $this->getRequestBody($request, 'message');
        $message_type = $this->getRequestBody($request, 'type');

        $message = new Message();
        $message->setMsg_text($message_text);
        $message->setType($message_type);
        $message->setUser_id($user_id);
        $message->setDiscussion_id($discu_id);
        $message->create();

        $discussion = new Discussion();
        $discussion->setId($discu_id);
        $discussion->load();
        $discussion->setLast_message($message->getId());
        $discussion->update();

        $status = "success";
        $messageJson = "Message sent";

        return $this->renderJson(array(
            "status" => $status,
            "message" => $messageJson
        ));
    }

    public function sendMessageFromProfil($request){
        $userArray = $this->verify_token($request);
        if ($userArray==false){
            return new Response(401, ['Content-Type' => 'application/json'], json_encode(array(
                "status" => "error",
                "message" => "Invalid authorized access"
            )));
        }

        $user_id = $userArray["id"];
        $user_to_send = $this->getRequestBody($request, 'user_id');
        $message_text = $this->getRequestBody($request, 'message');
        $message_type = $this->getRequestBody($request, 'type');

        $discussion = new Discussion();
        $discu_id = $discussion->getDiscuIdFromProfil($user_id, $user_to_send);

        $message = new Message();
        $message->setMsg_text($message_text);
        $message->setType($message_type);
        $message->setUser_id($user_id);
        $message->setDiscussion_id($discu_id);
        $message->create();

        $discussion->setId($discu_id);
        $discussion->load();
        $discussion->setLast_message($message->getId());
        $discussion->update();

        $status = "success";
        $messageJson = "Message sent";

        return $this->renderJson(array(
            "status" => $status,
            "message" => $messageJson
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
