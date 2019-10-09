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
            $group->post('/', $this->to('Home', 'index'), 'home2');
            // $group->get('/quanly', $this->to('Home', 'quanly'), 'quanly');
            // $group->get('/admin/login', $this->to('Admin\Auth', 'login'), 'admin_login');
        });

        $routing->post('/api/admin/login', $this->to('Admin\Auth', 'login'), 'api_admin_login');
        // $routing->get('/api/admin/me', $this->to('Admin\Dashboard', 'me'), 'api_admin_me');
        $routing->group('/api/admin', function ($group) {
            $group->get('/me', $this->to('Admin\Dashboard', 'me'), 'api_admin_me');
            $group->put('/me', $this->to('Admin\Dashboard', 'putMe'), 'api_admin_put_me');
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
