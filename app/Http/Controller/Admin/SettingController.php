<?php

namespace App\Http\Controller\Admin;

use App\Service\ApiService;
use App\Service\SettingService;

class SettingController extends ApiController
{
    protected function getService(): ApiService
    {
        return $this->container->get(SettingService::class);
    }

    public function save()
    {
        $data = $this->getJsonData();
        foreach ($data as $key => $value) {
            $model = $this->getService()->firstOrNew(['key' => $key]);
            $model->fill([
                'value' => $value,
            ]);
            $model->save();
        }
        return $this->json([
            'status' => true,
            'message' => 'Cập nhật thành công!',
        ]);
    }
}
