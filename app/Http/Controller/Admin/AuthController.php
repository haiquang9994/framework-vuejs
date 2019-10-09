<?php

namespace App\Http\Controller\Admin;

use App\Lib\Jwt\Jwt;
use App\Service\AdminService;
use App\Service\DBService;
use App\Service\TokenService;

class AuthController extends Controller
{
    public function login()
    {
        $data = $this->getJsonData();
        $result = $this->get(AdminService::class)->login($data);
        if ($result->status) {
            $admin = $result->admin;
            $token = $this->get(Jwt::class)->encode([
                'id' => $admin->id,
                'email' => $admin->email,
                'fullname' => $admin->fullname,
                'roles' => $admin->roles,
                'time' => time(),
            ], env('APP_KEY', 'app_key'));
            $this->container->get(TokenService::class)->insert([
                'token' => $token,
                'admin_id' => $admin->id,
            ]);
            return $this->json([
                'status' => true,
                'token' => $token,
                'user_data' => [
                    'id' => $admin->id,
                    'email' => $admin->email,
                    'fullname' => $admin->fullname,
                    'roles' => $admin->roles,
                ],
                'message' => $result->message,
            ]);
        }
        return $this->json([
            'status' => false,
            'message' => $result->message,
        ]);
    }

    public function logout()
    {
        if ($this->container->has('__token')) {
            $token = $this->container->get('__token');
            $this->container->get(TokenService::class)->where('token', $token)->delete();
        }
        return $this->json([
            'status' => true,
        ]);
    }
}
