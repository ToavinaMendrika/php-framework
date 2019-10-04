<?php
namespace App\Controllers;

use Framework\Controller\BaseController;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repositories\DiscussionRepository as Discussion;
use App\Repositories\MessageRepository as Message;
use Firebase\JWT\JWT;
use GuzzleHttp\Psr7\Response;

/**
* Description of MessageController
*
* @author David Rambolajaona <david.rambolajaon@esti.mg>
*/
class MessageController extends BaseController
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
    * Make the messages seen by the current user in the discussion
    *
    * @api
    * @param object $request
    * @return array
    */
    public function seeMessages($request){
        $userArray = $this->verify_token($request);
        if ($userArray==false){
            return new Response(401, ['Content-Type' => 'application/json'], json_encode(array(
                "status" => "error",
                "message" => "Invalid authorized access"
            )));
        }

        $user_id = $userArray["id"];
        $status = "success";
        $messageJson = "";

        $message = new Message();
        $discu_id = $this->getRequestBody($request, 'discussion_id');

        $count = $message->seeMessages($user_id, $discu_id);
        $messageJson = (string)$count . " messages sont vus";

        return $this->renderJson(array(
            "status" => $status,
            "message" => $messageJson,
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
