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
        $id = $this->request->attributes->get('id');
        if ($id === null) {
            $result = $this->getService()->getRecords($this->request);
            if ($result->status) {
                return [
                    'status' => true,
                    'pagination' => $result->pagination,
                    'data' => $result->data,
                ];
            }
        } else {
            $result = $this->getService()->getRecord($this->request);
            if ($result->status) {
                return [
                    'status' => true,
                    'data' => $result->data,
                ];
            }
        }
        return [
            'status' => false,
        ];
    }

    protected function ___post()
    {
        $data = $this->getJsonData();
        $result = $this->getService()->insertRecord($data);
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
        $data = $this->getJsonData();
        $result = $this->getService()->updateRecord($this->request, $data);
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

    protected function ___delete()
    {
        $result = $this->getService()->deleteRecord($this->request);
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
}
