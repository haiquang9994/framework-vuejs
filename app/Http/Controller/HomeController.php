<?php

namespace App\Http\Controller;

class HomeController extends BaseController
{
    public function api()
    {
        return $this->json([
            'name' => 'Framework Api.',
        ]);
    }

    public function index()
    {
        $this->data['message'] = "It's work!";
        return $this->render('default.twig');
    }
}
