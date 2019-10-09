<?php

use Phinx\Migration\AbstractMigration;

class Admins extends AbstractMigration
{
    public function change()
    {
        $this->table('admins', ['collation' => 'utf8_unicode_ci'])
            ->addColumn('email', 'string', ['null' => true, 'default' => null])
            ->addColumn('password', 'string', ['null' => true, 'default' => null, 'limit' => 62])
            ->addColumn('fullname', 'string', ['null' => true, 'default' => null])
            ->addColumn('roles', 'text', ['null' => true, 'default' => null])
            ->addColumn('active', 'boolean', ['default' => false])
            ->addTimestamps()
            ->create();
    }
}
