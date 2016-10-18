<?php
// Routes
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;
use \Firebase\JWT\JWT as JWT;
use \App\Controllers\JWTController as JWTController;


$app->add(function (Request $request,Response $response, $next) use ($app){
    if($request->getRequestTarget() == "/v1/authentificate"){
        $response = $next($request, $response);
        return $response;
    }
    $stringToken = $request->getHeader("Authorization")[0];
    if($stringToken == NULL){
        return $response->withJson(array("Connection"=>"Fail On Token", "Error"=>"No token Provided."));
    }else{
        $jsonObjectToken = json_decode($stringToken);
        try{
            JWT::decode($jsonObjectToken->jwt, JWTController::$secretKey, array('HS512'));
        }catch (Exception $e){
            return $response->withJson(array("Connection"=>"Fail On Token", "Error"=>$e->getMessage()));
        }
        $response = $next($request, $response);

        return $response;
    }

});