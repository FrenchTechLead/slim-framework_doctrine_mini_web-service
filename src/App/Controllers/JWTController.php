<?php

namespace App\Controllers;
use \App\Entities\User as User;
use \Firebase\JWT\JWT as JWT;

class JWTController
{
    static $secretKey = "just_a_stupid_key_now";

    public static function createToken(User $user)
    {
        //$tokenId    = base64_encode(mcrypt_create_iv(32));
        $issuedAt = time();
        $notBefore = $issuedAt + 10;             //Adding 10 seconds
        $expire = $notBefore + 60;            // Adding 60 seconds
        $serverName = "akram.fr"; // Retrieve the server name from config file

        /*
         * Create the token as an array
         */
        $data = [
            'iat' => $issuedAt,         // Issued at: time when the token was generated
            //'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
            'iss' => $serverName,       // Issuer
            'nbf' => $notBefore,        // Not before
            'exp' => $expire,           // Expire
            'data' => [                  // Data related to the signer user
                'userId' => $user->getId(), // userid from the users table
                'superuser' => $user->getIs_superuser(), // User name
            ]
        ];


        /*
         * Encode the array to a JWT string.
         * Second parameter is the key to encode the token.
         *
         * The output string can be validated at http://jwt.io/
         */
        $jwt = JWT::encode(
            $data,      //Data to be encoded in the JWT
            JWTController::$secretKey, // The signing key
            'HS512'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
        );

        $unencodedArray = ['jwt' => $jwt];
        return json_encode($unencodedArray);
    }


    public static function validateToken(\Slim\http\Request $request,\Slim\http\Response $response )
    {
        $authHeader = $request->getHeader('authorization');
        if ($authHeader) {

            $jwt = $authHeader[0];
            $jwt = json_decode($jwt)->jwt;

            if ($jwt) { //checking that the header contains Authorization field
                $token = JWT::decode($jwt, JWTController::$secretKey, array('HS512'));
                var_dump($token);die;

            } else {
                return $response->withJson(array("connection"=>"fail", "error"=>"Token not found in the request, the token should be added to Authorization header"),403);
            }

        }
    }
}