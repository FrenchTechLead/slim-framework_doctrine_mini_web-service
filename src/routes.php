<?php
// Routes
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;
use \App\Controllers\JWTController ;


$app->get('/bootstrap_css', function( Request $request, Response $response) use ($app){
    return $app->getContainer()->renderer->render($response, 'css/bootstrap.min.css')->withHeader("content-type","text/css");});

$app->get('/bootstrap_theme_css', function( Request $request, Response $response) use ($app){

    return $app->getContainer()->renderer->render($response, 'css/bootstrap-theme.min.css')->withHeader("content-type","text/css");});

$app->get('/bootstrap_js', function( Request $request, Response $response) use ($app){
    return $app->getContainer()->renderer->render($response, 'js/bootstrap.js')->withHeader("content-type","text/javascript");});

$app->get('/jquery', function( Request $request, Response $response) use ($app){
    return $app->getContainer()->renderer->render($response, 'js/jquery.js')->withHeader("content-type","text/javascript");});


$app->get('/', 'AuthentificationController:index');
$app->post('/', 'AuthentificationController:checkUser');

$app->get('/user', 'UserController:index');
$app->get('/superuser', 'SuperUserController:index');

$app->get("/VerifyToken", function( Request $request, Response $response) use ($app){
    \App\Controllers\JWTController::validateToken($request, $response);
});