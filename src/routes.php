<?php
// Routes
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;




$app->post('/v1/authentificate', 'AuthentificationController:checkUser')->setName('authentificate');

$app->get('/v1/empty_forms/all', 'UserController:getForms')->setName('getAllForms');

$app->get('/v1/users', 'SuperUserController:getAllUsers')->setName('getAllUsers');

$app->get('/v1/users/{id:\d+}', 'SuperUserController:getUser')->setName('getUser');

$app->post('/v1/users', 'SuperUserController:addUser')->setName('addUser');

$app->patch('/v1/users/{id:\d+}', 'SuperUserController:modifyUser')->setName('modifyUser');

$app->delete('/v1/users/{id:\d+}', 'SuperUserController:deleteUser')->setName('deleteUser');


