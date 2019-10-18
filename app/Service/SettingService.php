<?php

namespace App\Service;

use Illuminate\Database\Eloquent\Model;

class SettingService extends ApiService
{
    protected $model = '\App\Model\Setting';

    public function item(Model $record): array
    {
        return [
            'id' => $record->id,
            'key' => $record->key,
            'value' => $record->value,
        ];
    }
}
