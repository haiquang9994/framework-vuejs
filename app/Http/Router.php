<?php
namespace App\Http;

use App\Http\Middleware\AdminMiddleware;
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
            $group->get('/quanly', $this->to('Home', 'quanly'), 'quanly');
            $group->get('/admin/login', $this->to('Admin\Auth', 'login'), 'admin_login');
        });

        $routing->post('/api/admin/login', $this->to('Admin\Auth', 'login'), 'api_admin_login');
        $routing->group('/api/admin', function ($group) {

        });

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
