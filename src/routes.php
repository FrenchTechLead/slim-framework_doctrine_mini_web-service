<?php
// Routes


$app->get('/', 'AuthentificationController:index');
$app->post('/', 'AuthentificationController:checkUser');

$app->get('/user', 'UserController:index');
$app->get('/superuser', 'SuperUserController:index');