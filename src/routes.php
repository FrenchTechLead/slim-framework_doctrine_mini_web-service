<?php
// Routes
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;




$app->post('/v1/authentificate', 'AuthentificationController:checkUser');

$app->get('/v1/user/forms', 'UserController:getForms');

