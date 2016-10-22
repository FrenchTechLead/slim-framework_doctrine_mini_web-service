<?php
// Routes
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;



//Authentification
$app->post('/v1/authentificate', 'AuthentificationController:checkUser')->setName('authentificate');

// user operations
$app->get('/v1/users', 'SuperUserController:getAllUsers')->setName('getAllUsers');

$app->get('/v1/users/{id:\d+}', 'SuperUserController:getUser')->setName('getUser');

$app->post('/v1/users', 'SuperUserController:addUser')->setName('addUser');

$app->patch('/v1/users/{id:\d+}', 'SuperUserController:modifyUser')->setName('modifyUser');

$app->delete('/v1/users/{id:\d+}', 'SuperUserController:deleteUser')->setName('deleteUser');

//formversions opreration
$app->post('/v1/form_version', 'SuperUserController:createNewFormVersion')->setName('createNewForm');

$app->delete('/v1/form_version/{form_id:\d+}', 'SuperUserController:deleteFormVersion')->setName('deleteFormVersion');

$app->get('/v1/form_version/{form_id:\d+}', 'SuperUserController:getFormwhithQuestions')->setName('getFormwhithQuestions');
// Answered Form operations

$app->post('/v1/question/{form_id:\d+}', 'SuperUserController:createNewQuestion')->setName('createNewQuestion');

