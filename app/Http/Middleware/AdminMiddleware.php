<?php

namespace App\Http\Middleware;

use App\Lib\Jwt\Jwt;
use App\Model\Admin;
use App\Model\Token;
use App\Service\TokenService;
use DI\Container;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
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
        $authorization = $request->headers->get('Authorization');
        preg_match("/Bearer (.*)/", $authorization, $matchs);
        $token = $matchs[1] ?? null;
        if ($token === null) {
            $token = $request->query->get('_token');
        }
        if (is_string($token) && $this->container->get(Jwt::class)->decode($token)) {
            $tokenModel = $this->container->get(TokenService::class)
                ->where('token', $token)->where('last_time', '>', time() - 3600 * 24 * 30)
                ->first();
            if ($tokenModel instanceof Token) {
                $admin = $tokenModel->admin;
                if ($admin instanceof Admin && $admin->active) {
                    $this->container->set('__authed', $admin);
                    $this->container->set('__token', $token);
                    $tokenModel->last_time = time();
                    $tokenModel->save();
                    return;
                }
            }
        }
        return new JsonResponse([
            'message' => 'Who are you?'
        ], Response::HTTP_FORBIDDEN);
    }
}
