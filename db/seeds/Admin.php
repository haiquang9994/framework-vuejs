<?php

use Phinx\Seed\AbstractSeed;

class Admin extends AbstractSeed
{
    public function run()
    {
        $this->insert('admin', [
            'email' => 'admin@gmail.com',
            'password' => '$2y$10$dBJK.bL9im5AjCaelExyoum.0NuarSQ1pBlSYFN7/MOWoTufjE5H2',
            'fullname' => 'Admin',
            'roles' => '["ADMIN"]',
            'active' => true,
        ]);
    }
}
