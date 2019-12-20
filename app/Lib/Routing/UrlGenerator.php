<?php

namespace App\Lib\Routing;

use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGenerator as SymfonyUrlGenerator;

class UrlGenerator
{
    /**
     * @var Router
     */
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function url(string $routeName, array $parameters = [])
    {
        return $this->router->getGenerator()->generate($routeName, $parameters, SymfonyUrlGenerator::ABSOLUTE_URL);
    }

    public function path(string $routeName, array $parameters = [])
    {
        return $this->router->getGenerator()->generate($routeName, $parameters, SymfonyUrlGenerator::ABSOLUTE_PATH);
    }
}
