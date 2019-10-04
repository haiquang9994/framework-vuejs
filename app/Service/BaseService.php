<?php

namespace App\Service;

use Illuminate\Database\Capsule\Manager;
use Psr\Container\ContainerInterface;
use Evenement\EventEmitterTrait;

abstract class BaseService
{
    use EventEmitterTrait;

    protected $container;
    protected $db;
    protected $model;

    public function __construct(ContainerInterface $container, Manager $db)
    {
        $this->container = $container;
        $this->db = $db;
        $this->boot();
    }

    public function __call($method, $params)
    {
        if ($this->model) {
            return call_user_func_array([$this->model, $method], $params);
        }

        return call_user_func_array([$this->db->getDatabaseManager()->connection(), $method], $params);
    }

    public function addScope($callback)
    {
        if (is_callable($callback)) {
            $model = $this->model;
            $model::addGlobalScope($callback);
        }

        return $this;
    }

    public function boot()
    {
    }
}
