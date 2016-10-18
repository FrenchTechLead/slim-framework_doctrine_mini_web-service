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

        /** @var em $em */
        $em = $this->container->em;
        $user = JWTController::getUserFromRequest($request, $em);
        $responseArray["connected_user"]=array("id"=>$user->getId(), "email"=>$user->getEmail());

        $usersEntity = $em->getRepository("App\Entities\User");

        if($user != null){
            return $response->withJson($responseArray);
        }else{
            return $response->withJson(array("connection"=>"fail", "error"=>"The user id in the token is null."),403);
        }

    }
}