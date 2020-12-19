<?php

namespace App\Lib\Locale;

class Provider
{
    protected $locale;

    protected $defaultProvider;

    protected $values;

    public function __construct(string $locale, Provider $defaultProvider = null)
    {
        $this->locale = $locale;
        $this->defaultProvider = $defaultProvider;
        $this->init();
    }

    protected function init()
    {
        $data = require(__DIR__ . "/sourses/{$this->locale}.php");
        $this->loadValues($data);
    }

    protected function loadValues(array $data, string $prefix = '')
    {
        foreach ($data as $name => $value) {
            $key = ltrim($prefix . '.' . $name, '.');
            if (is_string($value)) {
                $this->values[$key] = $value;
            } elseif (is_array($value)) {
                $this->loadValues($value, $key);
            }
        }
    }

    public function translate(string $name)
    {
        if (isset($this->values[$name])) {
            return $this->values[$name];
        }
        if ($this->defaultProvider instanceof Provider) {
            return $this->defaultProvider->translate($name);
        }
        return "";
    }

    public function translateWithParams(string $name, array $parameters = [])
    {
    }
}
