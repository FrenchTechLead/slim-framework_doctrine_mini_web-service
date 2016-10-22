<?php
namespace App\Controllers;


use gamringer\JSONPatch\Operation\Exception;
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;
use \App\Controllers\JWTController as JWTController;
use \App\Entities\User as User;
use \Doctrine\ORM\EntityManager as em ;
use Doctrine\ORM\Query;
use \gamringer\JSONPatch\Patch as Patch;


class SuperUserController extends Controller{

    function getAllUsers(Request $request, Response $response){
        $responseArray = [];

        /** @var em $em */
        $em = $this->container->em;
        $user = JWTController::getUserFromRequest($request, $em);
        if($user->getIs_superUser() != 1)return $response->withJson(array("connection"=>"fail", "error"=>"You are not a superUser"),403);
        $responseArray["connected_user"]=array("id"=>$user->getId(), "email"=>$user->getEmail());

        $usersRepository = $em->getRepository("App\Entities\User");
        $allUsers = $usersRepository->findAll();
        $allUsersArray = [];

        foreach ($allUsers as $key => $parameter) {
            $allUsersArray[$parameter->getId()]=$parameter->toArray();
        }

        $responseArray["all_users"]= $allUsersArray;
        return $response->withJson($responseArray);

    }

    function getUser(Request $request, Response $response, $args){
        $responseArray = [];
        /** @var em $em */
        $em = $this->container->em;
        $user = JWTController::getUserFromRequest($request, $em);
        if($user->getIs_superUser() != 1)return $response->withJson(array("connection"=>"fail", "error"=>"You are not a superUser"),403);
        $responseArray["connected_user"]=array("id"=>$user->getId(), "email"=>$user->getEmail());
        $usersRepository = $em->getRepository("App\Entities\User");
        $user= $usersRepository->find($args["id"]);
        if($user == null)return $response->withJson(array("connection"=>"fail", "error"=>"The user with the id ".$args["id"]." does not exist"),403);
        $responseArray["fetched_user"]= $user->toArray();
        return $response->withJson($responseArray);

    }

    function addUser(Request $request, Response $response){
        $responseArray = [];
        /** @var em $em */
        $em = $this->container->em;
        $user = JWTController::getUserFromRequest($request, $em);
        if($user->getIs_superUser() != 1)return $response->withJson(array("connection"=>"fail", "error"=>"You are not a superUser"),403);
        $responseArray["connected_user"]=array("id"=>$user->getId(), "email"=>$user->getEmail());

        $fields = ['email','pass', 'is_superuser'];
        $data = $request->getParsedBody();
        $this->checkAllDataFields($data, $response,$fields); // just to check that all required data has been posted

        $checkEmailExists = $em->getRepository('App\Entities\User')
            ->findOneBy(array('email' => $data["email"]));
        if($checkEmailExists != null)return $response->withJson(array("connection"=>"fail", "error"=>"The email already exists"),500);

        $userToAdd = new \App\Entities\User();
        $userToAdd->setPassword($data["pass"]);
        $userToAdd->setEmail($data["email"]);
        $userToAdd->setIs_superuser($data["is_superuser"]);

        $em->persist($userToAdd);
        $em->flush();

        $responseArray["added_user"]= $userToAdd->toArray();
        return $response->withJson($responseArray);

    }


    function deleteUser(Request $request, Response $response, $args){
        $responseArray = [];
        /** @var em $em */
        $em = $this->container->em;
        $user = JWTController::getUserFromRequest($request, $em);
        if($user->getIs_superUser() != 1)return $response->withJson(array("connection"=>"fail", "error"=>"You are not a superUser"),403);
        $responseArray["connected_user"]=array("id"=>$user->getId(), "email"=>$user->getEmail());
        $usersRepository = $em->getRepository("App\Entities\User");
        $userToDelete= $usersRepository->find($args["id"]);
        if($userToDelete == null)return $response->withJson(array("connection"=>"fail", "error"=>"The user with the id ".$args["id"]." does not exist"),403);
        if($userToDelete->getId() == $user->getId())return $response->withJson(array("connection"=>"fail", "error"=>"You can not delete your own account"),403);
        $responseArray["toDelete_user"]= $userToDelete->toArray();
        $em->remove($userToDelete);
        $em->flush();
        return $response->withJson($responseArray);

    }

    function modifyUser(Request $request, Response $response, $args){
        $responseArray = [];
        /** @var em $em */
        $em = $this->container->em;
        $connected_user = JWTController::getUserFromRequest($request, $em);
        if($connected_user->getIs_superUser() != 1)return $response->withJson(array("connection"=>"fail", "error"=>"You are not a superUser"),403);
        $responseArray["connected_user"]=array("id"=>$connected_user->getId(), "email"=>$connected_user->getEmail());

        $usersRepository = $em->getRepository("App\Entities\User");

        /** @var User $userToPatch */
        $userToPatch = $usersRepository->find($args["id"]);
        if($userToPatch == null)return $response->withJson(array("connection"=>"fail", "error"=>"The user with the id ".$args["id"]." does not exist"),403);
        $userToPatchArray = $userToPatch->toArray();
        try{
            $patch = Patch::fromJSON($request->getBody());
            $patch->apply($userToPatchArray);
        }catch (Exception $e){
            return $response->withJson(array("connection"=>"fail", "error"=> $e->getMessage()),500);die;
        }


        $userToPatch->setEmail($userToPatchArray["email"]);
        $userToPatch->setIs_superuser($userToPatchArray["is_superuser"]);
        $userToPatch->setUpdated(new \DateTime());

        $em->persist($userToPatch);
        $em->flush();
        $responseArray["user_modified"]= $userToPatch->toArray();



        return $response->withJson($responseArray);

    }

    function createNewFormVersion(Request $request, Response $response){
        $responseArray = [];
        /** @var em $em */
        $em = $this->container->em;
        $connected_user = JWTController::getUserFromRequest($request, $em);
        if($connected_user->getIs_superUser() != 1)return $response->withJson(array("connection"=>"fail", "error"=>"You are not a superUser"),403);
        $responseArray["connected_user"]=array("id"=>$connected_user->getId(), "email"=>$connected_user->getEmail());

        $fields = ['titre'];
        $data = $request->getParsedBody();
        $this->checkAllDataFields($data, $response,$fields); // just to check that all required data has been posted

        $formVersion = new \App\Entities\FormVersions();
        $formVersion->setCreator($connected_user);
        $formVersion->setTitre($data["titre"]);
        $formVersion->setVersion(0);

        $em->persist($formVersion);
        $em->flush();

        return $response->withJson($formVersion->toArray());
    }

    function createNewQuestion(Request $request, Response $response, $args){
        $responseArray = [];
        /** @var em $em */
        $em = $this->container->em;
        $connected_user = JWTController::getUserFromRequest($request, $em);
        if($connected_user->getIs_superUser() != 1)return $response->withJson(array("connection"=>"fail", "error"=>"You are not a superUser"),403);
        $responseArray["connected_user"]=array("id"=>$connected_user->getId(), "email"=>$connected_user->getEmail());

        $fields = ['question'];
        $data = $request->getParsedBody();
        $this->checkAllDataFields($data, $response,$fields); // just to check that all required data has been posted

        $formVersionsRepository = $em->getRepository("App\Entities\FormVersions");
        $relatedForm = $formVersionsRepository->find($args["form_id"]);
        if($relatedForm == null)return $response->withJson(array("Operation"=>"fail", "error"=>"The form with the provided id (".$args["form_id"].") does not exist"),500);

        $question = new \App\Entities\Questions();

        $question->setQuestion($data["question"]);
        $question->setForm($relatedForm);
        $relatedForm->setUpdated(new \DateTime()); // updating the update time when adding a question to the form

        $em->persist($question);
        $em->flush();

        $responseArray["created_question"]=$question->toArray();

        return $response->withJson($responseArray);
    }

    public function deleteFormVersion(Request $request, Response $response, $args){
        $responseArray = [];
        /** @var em $em */
        $em = $this->container->em;
        $user = JWTController::getUserFromRequest($request, $em);
        if($user->getIs_superUser() != 1)return $response->withJson(array("connection"=>"fail", "error"=>"You are not a superUser"),403);
        $responseArray["connected_user"]=array("id"=>$user->getId(), "email"=>$user->getEmail());

        $formVersionsRepository = $em->getRepository("App\Entities\FormVersions");
        $formToDelete = $formVersionsRepository->find($args["form_id"]);
        if($formToDelete == null || $formToDelete->getId() == 0)return $response->withJson(array("Operation"=>"fail", "error"=>"The form with the provided id (".$args["form_id"].") does not exist"),500);

        $responseArray["form_deleted"] = $formToDelete->toArray();
        $em->remove($formToDelete); // deleting a form will delete all it's related questions thanks to cascade
        $em->flush();

        return $response->withJson($responseArray);

    }

    public function getFormwhithQuestions(Request $request, Response $response, $args){
        $responseArray = [];
        /** @var em $em */
        $em = $this->container->em;
        $user = JWTController::getUserFromRequest($request, $em);
        if($user->getIs_superUser() != 1)return $response->withJson(array("connection"=>"fail", "error"=>"You are not a superUser"),403);
        $responseArray["connected_user"]=array("id"=>$user->getId(), "email"=>$user->getEmail());

        $formVersionsRepository = $em->getRepository("App\Entities\FormVersions");
        $requestedForm = $formVersionsRepository->find($args["form_id"]);
        if($requestedForm == null || $requestedForm->getId() == 0)return $response->withJson(array("Operation"=>"fail", "error"=>"The form with the provided id (".$args["form_id"].") does not exist"),500);

        $responseArray["requested_form"] = $requestedForm->toArray();

        //$questionsRepository = $em->getRepository("App\Entities\Questions");
        //$associatedQuestions = $questionsRepository->findOneBy(["form"=>$requestedForm]);
        //var_dump($associatedQuestions->toArray());die;

        //$responseArray["associated_questions"] = $associatedQuestions->toArray();

        return $response->withJson($responseArray);

    }


}