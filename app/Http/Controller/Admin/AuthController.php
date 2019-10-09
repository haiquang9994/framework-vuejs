<?php

namespace App\Http\Controller\Admin;

use App\Lib\Jwt\Jwt;
use App\Service\AdminService;

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

    public function get2()
    {
        return $this->json([
            'a' => 'b',
            'c' => 'd',
        ]);
    }
}
