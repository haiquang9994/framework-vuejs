<?php

namespace App\Lib\Locale;

class Translate
{
    protected $defaultLocale;

    protected $defaultProvider;

    protected $providers = [];

    public function __construct(string $defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;

        $this->providers[$defaultLocale] = $this->defaultProvider = new Provider($defaultLocale);
    }

    public function loadProvider(string $locale)
    {
        $this->providers[$locale] = new Provider($locale, $this->defaultProvider);
        if ($this->defaultLocale = $locale) {
            $this->defaultProvider = $this->providers[$locale];
        }
    }

    protected function getProvider(string $locale): ?Provider
    {
        if (isset($this->providers[$locale])) {
            return $this->providers[$locale];
        }
        throw new \Exception("Provider of locale \"{$locale}\" not found.");
    }

    public function translate(string $name, string $locale)
    {
        return $this->getProvider($locale)->translate($name);
    }

    public function translateWithParams(string $name, array $parameters = [], string $locale)
    {
        return $this->getProvider($locale)->translateWithParams($name, $parameters);
    }
}
