<?php

namespace App\Controllers;


class UserController extends Controller
{
    public function index( $request, $response){
        echo'user';die;

        return $this->container->renderer->render($response, "user.html");

    }

}