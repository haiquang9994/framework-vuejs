<?php

namespace App\ServiceProvider;

use App\Lib\Locale\Translate;
use DI\ContainerBuilder;
use Pho\Core\ServiceProviderInterface;

class LocaleServiceProvider implements ServiceProviderInterface
{
    public function register(ContainerBuilder $containerBuilder, array $opts = [])
    {
        $def = [];

        $def['translator'] = function ($c) {
            return new Translate($c->get('translator.default_locale'));
        };

        $def = array_merge($def, $opts);

        $containerBuilder->addDefinitions($def);
    }
}
