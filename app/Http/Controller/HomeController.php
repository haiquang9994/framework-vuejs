<?php
namespace App\Http\Controller;

class HomeController extends BaseController
{
    public function index()
    {
        $this->data['name'] = 'Nguyen Hai Quang';
        return $this->render('default.twig');
        return $this->text('Phở ngon quá !!!');
    }

    public function quanly()
    {
        return $this->redirectFor('admin');
    }

    public function options()
    {
        return $this->json([]);
    }
}
