<?php

namespace App\Controllers;

use Slim\Http\Request as Request;
use Slim\Http\Response as Response;
use \App\Controllers\JWTController as JWTController;
use \App\Entities\User as User;
use \Doctrine\ORM\EntityManager as em ;


class UserController extends Controller
{


    public function getForms(Request $request, Response $response){
        $responseArray = [];
        $token = JWTController::decodeToken($request, $response);
        $userId = $token->data->userId;

        /** @var em $em */
        $em = $this->container->em;
        $usersEntity = $em->getRepository("App\Entities\User");
        $user = $usersEntity->findBy(array("id"=>$userId))[0];
        $responseArray["userEmail"]= $user->getEmail();
        if($user != null){
            return $response->withJson($responseArray);
        }else{
            return $response->withJson(array("connection"=>"fail", "error"=>"The user id in the token is null."),403);
        }

    }
}