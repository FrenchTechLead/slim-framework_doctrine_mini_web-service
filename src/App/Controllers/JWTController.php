<?php

namespace App\Controllers;
use \App\Entities\User as User;
use \Firebase\JWT\JWT as JWT;

class JWTController
{
    static $secretKey = "just_a_stupid_key_now";

    public static function createToken(User $user){

        //$tokenId    = base64_encode(mcrypt_create_iv(32));
        $issuedAt = time();
        $notBefore = $issuedAt ;             //Adding 10 seconds
        $expire = $issuedAt + (5*60*60*60);            // The token expires after 5 hours
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


        $jwt = JWT::encode(
            $data,      //Data to be encoded in the JWT
            JWTController::$secretKey, // The signing key
            'HS512'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
        );

        $unencodedArray = ['jwt' => $jwt];
        return json_encode($unencodedArray);
    }


    public static function decodeToken(\Slim\http\Request $request){
        $headerObject = $request->getHeader('authorization')[0];
        $jwt = json_decode($headerObject)->jwt;
        $token =  JWT::decode($jwt, JWTController::$secretKey, array('HS512'));
        return $token;
    }
}