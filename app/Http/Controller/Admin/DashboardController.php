<?php

namespace App\Http\Controller\Admin;

class DashboardController extends Controller
{
    public function index()
    {
        return $this->render('admin/dashboard.html.twig');
    }

    /** Authed */
    public function me()
    {
        if ($this->container->has('__authed')) {
            $admin = $this->container->get('__authed');
            return $this->json([
                'status' => true,
                'user_data' => [
                    'id' => $admin->id,
                    'email' => $admin->email,
                    'fullname' => $admin->fullname,
                    'roles' => $admin->roles,
                ],
            ]);
        }
        return $this->json([
            'status' => false,
        ]);
    }

    /** Authed */
    public function putMe()
    {
        if ($this->container->has('__authed')) {
            $admin = $this->container->get('__authed');
            $admin->fullname = $this->getJsonData('fullname');
            $admin->save();
            return $this->json([
                'status' => true,
                'user_data' => [
                    'id' => $admin->id,
                    'email' => $admin->email,
                    'fullname' => $admin->fullname,
                    'roles' => $admin->roles,
                ],
                'message' => 'Cập nhật thành công!',
            ]);
        }
        return $this->json([
            'status' => false,
        ]);
    }
}
