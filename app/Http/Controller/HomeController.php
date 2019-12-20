<?php

namespace App\Http\Controller;

class HomeController extends BaseController
{
    public function index()
    {
        $this->data['message'] = "It's work!";
        return $this->render('default.twig');
    }
}
