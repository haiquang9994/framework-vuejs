<?php

namespace App\Http\Middleware;

use App\Lib\Jwt\Jwt;
use App\Model\Admin;
use App\Service\TokenService;
use DI\Container;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAdminMiddleware
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request)
    {
        $authorization = $request->headers->get('authorization');
        preg_match("/Bearer (.*)/", $authorization, $matchs);
        $token = $matchs[1] ?? null;
        if ($token === null) {
            $token = $request->query->get('_token');
        }
        if (is_string($token) && $this->container->get(Jwt::class)->decode($token, env('APP_KEY', 'app_key'))) {
            if ($tokenModel = $this->container->get(TokenService::class)->where('token', $token)->first()) {
                $admin = $tokenModel->admin;
                if ($admin instanceof Admin && $admin->active) {
                    $this->container->set('__authed', $admin);
                    $this->container->set('__token', $token);
                    return;
                }
            }
        }
        return new JsonResponse([
            'message' => 'Who are you?'
        ], Response::HTTP_FORBIDDEN);
    }
}
