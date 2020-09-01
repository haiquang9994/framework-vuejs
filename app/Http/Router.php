<?php

namespace App\Http;

use App\Http\Middleware\AdminMiddleware;
use Pho\Routing\RouteLoader;
use Pho\Routing\Routing;

class Router extends RouteLoader
{
    private function to($controller, $method)
    {
        return '\\App\\Http\Controller\\' . $controller . 'Controller::' . $method;
    }

    public function routes(Routing $routing)
    {
        $routing->group('/', function ($group) {
            $group->get('/', $this->to('Home', 'index'), 'home');
        });

        $routing->get('/api', $this->to('Home', 'api'), 'api');
        $routing->post('/api/admin/login', $this->to('Admin\Auth', 'login'), 'api_admin_login');
        $routing->group('/api/admin', function ($group) {
            $group->map('POST|GET', '/finder/connector', $this->to('Admin\Finder', 'connector'), 'finder_connector');
            $group->map('POST', '/finder/upload', $this->to('Admin\Finder', 'upload'), 'finder_upload');

            $group->get('/me', $this->to('Admin\Dashboard', 'me'), 'api_admin_me');
            $group->put('/me', $this->to('Admin\Dashboard', 'putMe'), 'api_admin_put_me');
            $group->delete('/logout', $this->to('Admin\Auth', 'logout'), 'api_admin_logout');

            $group->map('GET|POST', '/post/category', $this->to('Admin\PostCategory', 'index'), 'api_admin_post_category');
            $group->map('GET|PUT|DELETE', '/post/category/{id}', $this->to('Admin\PostCategory', 'index'), 'api_admin_post_category_');

            $group->map('GET|POST', '/post', $this->to('Admin\Post', 'index'), 'api_admin_post');
            $group->map('GET|PUT|DELETE', '/post/{id}', $this->to('Admin\Post', 'index'), 'api_admin_post_');

            $group->map('GET', '/settings', $this->to('Admin\Setting', 'index'), 'api_admin_setting');
            $group->map('PUT', '/settings', $this->to('Admin\Setting', 'save'), 'api_admin_setting_');
        }, [
            '_before' => [
                AdminMiddleware::class,
            ],
        ]);
    }
}
