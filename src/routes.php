<?php
// Routes
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;




$app->post('/v1/authentificate', 'AuthentificationController:checkUser')->setName('authentificate');

$app->get('/v1/empty_forms/all', 'UserController:getForms')->setName('getAllForms');

