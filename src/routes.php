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

$app->get('/v1/answered_form/{form_id:\d+}', 'UserController:getFilledForm')->setName('getFilledForm');



//formVersions opreration
$app->post('/v1/form_version', 'SuperUserController:createNewFormVersion')->setName('createNewForm');

$app->delete('/v1/form_version/{form_id:\d+}', 'SuperUserController:deleteFormVersion')->setName('deleteFormVersion');

$app->get('/v1/form_version/{form_id:\d+}', 'SuperUserController:getFormwhithQuestions')->setName('getFormWhithQuestions');

$app->post('/v1/question/{form_id:\d+}', 'SuperUserController:createNewQuestion')->setName('createNewQuestion');

$app->delete('/v1/question/{question_id:\d+}', 'SuperUserController:deleteQuestion')->setName('deleteQuestion');



// public routes
$app->get('/', function( Request $request, Response $response) use ($app){
    return $app->getContainer()->renderer->render($response, 'index.html')->withHeader("content-type","text/html");});

$app->get('/bootstrap_css', function( Request $request, Response $response) use ($app){
    return $app->getContainer()->renderer->render($response, 'css/bootstrap.min.css')->withHeader("content-type","text/css");});

$app->get('/sweetAlert_css', function( Request $request, Response $response) use ($app){
    return $app->getContainer()->renderer->render($response, 'css/sweetAlert.css')->withHeader("content-type","text/css");});

$app->get('/bootstrap_theme_css', function( Request $request, Response $response) use ($app){

    return $app->getContainer()->renderer->render($response, 'css/bootstrap-theme.min.css')->withHeader("content-type","text/css");});

$app->get('/bootstrap_js', function( Request $request, Response $response) use ($app){
    return $app->getContainer()->renderer->render($response, 'js/bootstrap.js')->withHeader("content-type","text/javascript");});

$app->get('/sweetAlert_js', function( Request $request, Response $response) use ($app){
    return $app->getContainer()->renderer->render($response, 'js/sweetAlert.js')->withHeader("content-type","text/javascript");});

$app->get('/jquery', function( Request $request, Response $response) use ($app){
    return $app->getContainer()->renderer->render($response, 'js/jquery.js')->withHeader("content-type","text/javascript");});

$app->get('/myScripts', function( Request $request, Response $response) use ($app){
    return $app->getContainer()->renderer->render($response, 'js/myScripts.js')->withHeader("content-type","text/javascript");});

$app->get('/jquery_cookie', function( Request $request, Response $response) use ($app){
    return $app->getContainer()->renderer->render($response, 'js/jquery_cookie.js')->withHeader("content-type","text/javascript");});

$app->get('/user_space', function( Request $request, Response $response) use ($app){
    return $app->getContainer()->renderer->render($response, 'user.html')->withHeader("content-type","text/html");});

$app->get('/superuser_space', function( Request $request, Response $response) use ($app){
    return $app->getContainer()->renderer->render($response, 'superuser.html')->withHeader("content-type","text/html");});