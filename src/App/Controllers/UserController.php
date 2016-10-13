<?php

namespace App\Controllers;


class UserController extends Controller
{
    public function index( $request, $response){

        return $this->container->renderer->render($response, "user.html");

    }

}