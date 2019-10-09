<?php

namespace App\Http\Middleware;

use App\Lib\Jwt\Jwt;
use App\Model\Admin;
use App\Service\AdminService;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAdminMiddleware
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request)
    {
        $authorization = $request->headers->get('authorization');
        preg_match("/Bearer (.*)/", $authorization, $matchs);
        $token = $matchs[1] ?? null;
        if (is_string($token) && $data = $this->container->get(Jwt::class)->decode($token, env('APP_KEY', 'app_key'))) {
            $duration = intval(env('TOKEN_DURATION', 3600));
            if ((time() - $data->time) > $duration) {
                return new JsonResponse([
                    'status' => false,
                ], 401);
            }
            $admin = $this->container->get(AdminService::class)->find($data->id);
            if ($admin instanceof Admin && $admin->active) {
                $this->container->set('__authed', $admin);
                return;
            }
        }
        return new JsonResponse([
            'message' => 'Who are you?'
        ], Response::HTTP_FORBIDDEN);
    }
}
