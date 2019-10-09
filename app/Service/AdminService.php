<?php

namespace App\Service;

use App\Lib\Data\Result;
use App\Model\Admin;

class AdminService extends BaseService
{
    protected $model = '\App\Model\Admin';

    public function login(array $data)
    {
        $result = new Result([
            'status' => false,
        ]);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        if (!$email) {
            $result->message = 'Chưa nhập email!';
            return $result;
        }
        if (!$password) {
            $result->message = 'Chưa nhập mật khẩu!';
            return $result;
        }
        $admin = $this->where('email', $email)->first();
        if (!$admin instanceof Admin) {
            $result->message = 'Email không tồn tại!';
            return $result;
        }
        if (!$admin->active) {
            $result->message = 'Tài khoản của bạn đã bị khoá!';
            return $result;
        }
        if (!$admin->verify($password)) {
            $result->message = 'Mật khẩu không đúng!';
            return $result;
        }
        $result->status = true;
        $result->admin = $admin;
        $result->message = 'Đăng nhập thành công!';
        return $result;

    }
}
