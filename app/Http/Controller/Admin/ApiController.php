<?php

namespace App\Http\Controller\Admin;

use App\Service\ApiService;

abstract class ApiController extends Controller
{
    abstract protected function getService(): ApiService;

    public function index()
    {
        $method = '___'.strtolower($this->request->getMethod());
        $result = call_user_func_array([$this, $method], []);
        return $this->json($result);
    }

    protected function ___get()
    {
        return [
            'status' => true,
            'id' => 1,
        ];
    }

    protected function ___post()
    {
        $data = $this->getJsonData();
        $result = $this->getService()->insert($data);
        if ($result->status) {
            return [
                'status' => true,
                'id' => $result->model->id,
            ];
        }
        return [
            'status' => false,
        ];
    }

    protected function ___put()
    {
        return [
            'status' => true,
            'id' => 1,
        ];
    }

    protected function ___delete()
    {
        return [
            'status' => true,
            'id' => 1,
        ];
    }
}
