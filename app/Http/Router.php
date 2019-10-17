<?php
namespace App\Http;

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ApiAdminMiddleware;
use Pho\Routing\RouteLoader;
use Pho\Routing\Routing;

class Router extends RouteLoader
{
    private function to($controller, $method)
    {
        return '\\App\\Http\Controller\\'.$controller.'Controller::'.$method;
    }

    public function routes(Routing $routing)
    {
        $routing->group('/', function ($group) {
            $group->get('/', $this->to('Home', 'index'), 'home');
        });

        $routing->post('/api/admin/login', $this->to('Admin\Auth', 'login'), 'api_admin_login');
        $routing->group('/api/admin', function ($group) {
            $group->map('POST|GET', '/finder/connector', $this->to('Admin\Finder', 'connector'), 'finder_connector');
            $group->get('/me', $this->to('Admin\Dashboard', 'me'), 'api_admin_me');
            $group->put('/me', $this->to('Admin\Dashboard', 'putMe'), 'api_admin_put_me');
            $group->delete('/logout', $this->to('Admin\Auth', 'logout'), 'api_admin_logout');

            $group->map('GET|POST', '/post', $this->to('Admin\Post', 'index'), 'api_admin_post');
            $group->map('GET|PUT|DELETE', '/post/{id}', $this->to('Admin\Post', 'index'), 'api_admin_post_');
        }, [
            '_before' => [
                ApiAdminMiddleware::class,
            ],
        ]);

        $routing->group('/admin', function ($group) {
            $group->get('/', $this->to('Admin\Dashboard', 'index'), 'admin/');
            $group->get('', $this->to('Admin\Dashboard', 'index'), 'admin');
        }, [
            '_before' => [
                AdminMiddleware::class,
            ],
        ]);
    }
}
