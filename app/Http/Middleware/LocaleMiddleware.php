<?php

namespace App\Http\Middleware;

use DI\Container;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class LocaleMiddleware
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request)
    {
        $locale = $request->attributes->get('_locale');
        if (!$locale) {
            $locale = $request->query->get('locale');
        }
        if (!$locale) {
            $locale = $request->getSession()->get('locale');
        }
        if (!$locale) {
            $locale = 'en';
        }
        $request->getSession()->set('locale', $locale);
        $this->container->get('translator')->loadProvider($locale);
        $this->container->set('_locale', $locale);
    }
}