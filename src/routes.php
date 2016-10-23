<?php
// Routes
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;



//Authentification
$app->post('/v1/authentificate', 'AuthentificationController:checkUser')->setName('authentificate');



// SuperUser operations
$app->get('/v1/users', 'SuperUserController:getAllUsers')->setName('getAllUsers');

$app->get('/v1/users/{id:\d+}', 'SuperUserController:getUser')->setName('getUser');

$app->post('/v1/users', 'SuperUserController:addUser')->setName('addUser');

$app->patch('/v1/users/{id:\d+}', 'SuperUserController:modifyUser')->setName('modifyUser');

$app->delete('/v1/users/{id:\d+}', 'SuperUserController:deleteUser')->setName('deleteUser');



//userOpérations
$app->post('/v1/answered_form/{linked_form_id:\d+}', 'UserController:createFilledForm')->setName('createFilledForm');

$app->post('/v1/answer/{linked_form_id:\d+}', 'UserController:createAnswer')->setName('createAnswer');



//formVersions opreration
$app->post('/v1/form_version', 'SuperUserController:createNewFormVersion')->setName('createNewForm');

$app->delete('/v1/form_version/{form_id:\d+}', 'SuperUserController:deleteFormVersion')->setName('deleteFormVersion');

$app->get('/v1/form_version/{form_id:\d+}', 'SuperUserController:getFormwhithQuestions')->setName('getFormWhithQuestions');

$app->post('/v1/question/{form_id:\d+}', 'SuperUserController:createNewQuestion')->setName('createNewQuestion');

$app->delete('/v1/question/{question_id:\d+}', 'SuperUserController:deleteQuestion')->setName('deleteQuestion');

