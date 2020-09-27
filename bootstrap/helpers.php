<?php

defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('ROOT_PATH') or define('ROOT_PATH', realpath(dirname(__DIR__)));

if (!function_exists('app_path')) {
    function app_path(string $path): string
    {
        return rtrim(ROOT_PATH . DS . 'app' . DS . ltrim($path, '/'), '/');
    }
}
if (!function_exists('storage_path')) {
    function storage_path(string $path): string
    {
        return rtrim(ROOT_PATH . DS . 'storage' . DS . ltrim($path, '/'), '/');
    }
}
if (!function_exists('resources_path')) {
    function resources_path(string $path): string
    {
        return rtrim(ROOT_PATH . DS . 'resources' . DS . ltrim($path, '/'), '/');
    }
}
if (!function_exists('public_path')) {
    function public_path(string $path): string
    {
        return rtrim(ROOT_PATH . DS . 'public' . DS . ltrim($path, '/'), '/');
    }
}
if (!function_exists('env')) {
    function env(string $key, $default = null)
    {
        $value = getenv($key);
        if ($value === false) {
            return $default;
        }
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }
        $len = strlen($value);
        if ($len > 1 && ($value[0] == '"' && $value[$len - 1] == '"')) {
            return substr($value, 1, -1);
        }
        return $value;
    }
}

if (function_exists('c')) {
    throw new Exception('function "c" is already existed !');
} else {
    function c(string $key = null)
    {
        global $pho_container;
        return is_null($key) ? $pho_container : $pho_container->get($key);
    }
}
