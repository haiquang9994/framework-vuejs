<?php

use Phinx\Migration\AbstractMigration;

class Tokens extends AbstractMigration
{
    public function change()
    {
        $this->table('tokens', ['collation' => 'utf8_unicode_ci'])
            ->addColumn('admin_id', 'integer', ['null' => true, 'default' => null])
            ->addColumn('token', 'string', ['limit' => 255, 'null' => true, 'default' => null])
            ->addColumn('last_time', 'integer', ['null' => true, 'default' => null])
            ->addForeignKey('admin_id', 'admins', 'id', ['delete' => 'CASCADE'])
            ->addTimestamps()
            ->create();
    }
}
