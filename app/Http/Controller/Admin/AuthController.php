<?php

namespace App\Http\Controller\Admin;

use App\Lib\Jwt\Jwt;
use App\Service\AdminService;
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
                'first_time' => time(),
            ]);
            $this->container->get(TokenService::class)->createNew([
                'token' => $token,
                'admin_id' => $admin->id,
                'last_time' => time(),
            ])->save();
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
