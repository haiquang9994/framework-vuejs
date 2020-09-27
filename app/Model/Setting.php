<?php

namespace App\Model;

class Setting extends Base
{
    protected $table = 'setting';

    protected $fillable = [
        'key', 'value',
    ];

    protected $json_fields = [
        'website_name' => 'string',
    ];

    public $timestamps = false;

    protected function setValueAttribute($value)
    {
        $type = $this->json_fields[$this->key] ?? 'string';
        if ($type === 'array') {
            $this->attributes['value'] = json_encode($value);
        } elseif ($type === 'boolean') {
            $this->attributes['value'] = $value ? 1 : 0;
        } else {
            $this->attributes['value'] = $value;
        }
    }

    protected function getValueAttribute($value)
    {
        $type = $this->json_fields[$this->key] ?? 'string';
        if ($type === 'array') {
            $value = json_decode($value, true);
        } elseif ($type === 'boolean') {
            $value = boolval($value);
        }
        return $value;
    }
}
