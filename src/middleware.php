<?php
// Routes
use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;
use \Firebase\JWT\JWT as JWT;
use \App\Controllers\JWTController as JWTController;

$public_routes = $settings["settings"]["public_routes"];

//Cross-Origin-middle-ware
$corsOptions = array(
    "origin" => "*",
    "exposeHeaders" => array("Authorization","Content-Type","Access-Control-Expose-Headers", "X-Requested-With", "X-authentication", "X-client"),
    "allowMethods" => array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS')
);
$cors = new \CorsSlim\CorsSlim($corsOptions);

$app->add($cors);


//JsonWebToken middleWare
$app->add(function (Request $request,Response $response, $next) use ($app, $public_routes){

    $route = $request->getRequestTarget(); // these routes don't need Authentification
    if(in_array($route,  $public_routes)){
        $response = $next($request, $response);
        return $response;
    }
    $stringToken = $request->getHeader("Authorization")[0];
    if($stringToken == NULL){
        return $response->withJson(array("Connection"=>"Fail On Token", "Error"=>"No token Provided."),401);
    }else{
        $jsonObjectToken = json_decode($stringToken);
        try{
            JWT::decode($jsonObjectToken->jwt, JWTController::$secretKey, array('HS512'));
        }catch (Exception $e){
            return $response->withJson(array("Connection"=>"Fail On Token", "Error"=>$e->getMessage()),401);
        }
        $token = JWTController::decodeToken($request, $response);
        $userId = $token->data->userId;

        /** @var em $em */
        $em = $app->getContainer()->em;
        $usersEntity = $em->getRepository("App\Entities\User");

        $user = $usersEntity->findBy(array("id"=>$userId))[0];
        $response = $response->withHeader("Authorization",JWTController::createToken($user));
        $response = $next($request, $response);
        return $response;
    }

});

