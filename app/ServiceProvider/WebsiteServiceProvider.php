<?php

namespace App\ServiceProvider;

use DI\ContainerBuilder;
use Pho\Core\ServiceProviderInterface;
use App\Lib\Routing\UrlGenerator;
use Psr\Container\ContainerInterface;
use Twig\TwigFunction;
use Twig_Environment;

use function DI\decorate;
use function DI\get;

class WebsiteServiceProvider implements ServiceProviderInterface
{
    public function register(ContainerBuilder $containerBuilder, array $opts = [])
    {
        $def = [];

        $def['url_generator'] = get(UrlGenerator::class);

        $def[Twig_Environment::class] = decorate(function (Twig_Environment $twig, ContainerInterface $c) {
            $twig->addFunction(new TwigFunction('url', function (string $routeName, array $parameters = []) use ($c) {
                return $c->get('url_generator')->url($routeName, $parameters);
            }));
            $twig->addFunction(new TwigFunction('path', function (string $routeName, array $parameters = []) use ($c) {
                return $c->get('url_generator')->path($routeName, $parameters);
            }));
            return $twig;
        });

        $def = array_merge($def, $opts);

        $containerBuilder->addDefinitions($def);
    }
}
