<?php

use Phinx\Seed\AbstractSeed;

class Admin extends AbstractSeed
{
    public function run()
    {
        $this->insert('admins', [
            'email' => 'admin@gmail.com',
            'password' => '$2y$10$PXTZ8FP25K8yekZp37trgeeZl7t3nijy4w5eu31VxgA.NIl7op1FS',
            'fullname' => 'Admin',
            'roles' => '["ADMIN"]',
            'active' => true,
        ]);
    }
}
