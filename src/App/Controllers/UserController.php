<?php

namespace App\Controllers;

use Slim\Http\Request as Request;
use Slim\Http\Response as Response;
use \App\Controllers\JWTController as JWTController;
use \App\Entities\User as User;
use \Doctrine\ORM\EntityManager as em ;


class UserController extends Controller{


    public function createFilledForm(Request $request, Response $response, $args){
        $responseArray = [];
        /** @var em $em */
        $em = $this->container->em;
        $connected_user = JWTController::getUserFromRequest($request, $em);
        if($connected_user->getIs_superUser() == 1)return $response->withJson(array("connection"=>"fail", "error"=>"You are connected as admin."),403);
        $responseArray["connected_user"]=array("id"=>$connected_user->getId(), "email"=>$connected_user->getEmail());

        $formVersionsRepository = $em->getRepository("App\Entities\FormVersions");
        $linkedForm = $formVersionsRepository->find($args["linked_form_id"]);
        if($linkedForm == null)return $response->withJson(array("Operation"=>"fail", "error"=>"The form with the provided id (".$args["form_id"].") does not exist"),500);

        $answeredForm = new \App\Entities\FormAnswered();
        $answeredForm->setUser($connected_user);
        $answeredForm->setFormlinked($linkedForm);

        $em->persist($answeredForm);
        $em->flush();

        $responseArray["filling_form"]= $answeredForm->toArray();

        return $response->withJson($responseArray);

    }

    public function createAnswer(Request $request, Response $response, $args){
        $responseArray = [];
        /** @var em $em */
        $em = $this->container->em;
        $connected_user = JWTController::getUserFromRequest($request, $em);
        if($connected_user->getIs_superUser() == 1)return $response->withJson(array("connection"=>"fail", "error"=>"You are connected as admin."),403);
        $responseArray["connected_user"]=array("id"=>$connected_user->getId(), "email"=>$connected_user->getEmail());

        $fields = ['answer', 'question_id'];
        $data = $request->getParsedBody();
        if(! $this->checkAllDataFields($data,$fields))return $response->withJson(["error"=>["message"=>"Missing data"]],400);// just to check that all required data has been posted

        $formVersionsRepository = $em->getRepository("App\Entities\FormAnswered");
        $linkedForm = $formVersionsRepository->find($args["linked_form_id"]);
        if($linkedForm == null) return $response->withJson(["Operation"=>"fail","message"=>"The form with the provided id (".$args["linked_form_id"].") does not exist"],500);

        $questionsRepository = $em->getRepository("App\Entities\Questions");
        $linkedQuestion = $questionsRepository->find($data["question_id"]);
        if($linkedQuestion == null) return $response->withJson(["Operation"=>"fail","message"=>"The question with the provided id (".$data["question_id"].") does not exist"],500);

        $questions = $linkedForm->getFormLinked()->getQuestions();
        $check = false;
        foreach ($questions as $q){
            if ($q->getId() == $data["question_id"])$check = true;
        }
        if(! $check)return $response->withJson(["Operation"=>"fail","message"=>"The question with the provided id (".$data["question_id"].") is not part of the form provided id (".$args["linked_form_id"].")"],500);

        $answersRepository = $em->getRepository("\App\Entities\Answers");
        $potentialExistingAnswer = $answersRepository->findBy(["question"=>$linkedQuestion, "formAnswered"=>$linkedForm]);
        if($potentialExistingAnswer != null) return $response->withJson(["Operation"=>"fail","message"=>"There is already an entry for this form and question, please try to patch or delete first"],500);


        $answer = new \App\Entities\Answers();

        $answer->setQuestion($linkedQuestion);
        $answer->setFormAnswered($linkedForm);
        $answer->setAnswer($data["answer"]);

        $em->persist($answer);
        $em->flush();

        $responseArray["answer_created"]= $answer->toArray();

        return $response->withJson($responseArray);

    }

    public function getFilledForm(Request $request, Response $response, $args){
        $responseArray = [];
        /** @var em $em */
        $em = $this->container->em;
        $connected_user = JWTController::getUserFromRequest($request, $em);
        if($connected_user->getIs_superUser() == 1)return $response->withJson(array("connection"=>"fail", "error"=>"You are connected as admin."),403);
        $responseArray["connected_user"]=array("id"=>$connected_user->getId(), "email"=>$connected_user->getEmail());

        $answeredFormRepository = $em->getRepository("\App\Entities\FormAnswered");
        $requestedForm = $answeredFormRepository->find($args["form_id"]);
        if($requestedForm == null) return $response->withJson(["Operation"=>"fail","message"=>"The requested form with id = ".$args['form_id']." does not exist."],500);

        if($requestedForm->getUser()->getId() != $connected_user->getId()) return $response->withJson(["Operation"=>"fail","message"=>"You need to be the author of this form to be able to consult it."],500);

        $responseArray["requested_filled_form"]= $requestedForm->toArray();

        return $response->withJson($responseArray);
    }
}