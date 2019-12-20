<?php

namespace App\Lib\Support;

class Result
{
    /**
     * @var array
     */
    protected $data = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        return $this->data[$name] ?? null;
    }
}
