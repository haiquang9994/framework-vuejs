<?php

use Phinx\Migration\AbstractMigration;

class Tokens extends AbstractMigration
{
    public function change()
    {
        $this->table('tokens', ['collation' => 'utf8_unicode_ci'])
            ->addColumn('admin_id', 'integer', ['null' => true, 'default' => null])
            ->addColumn('access_token', 'string', ['limit' => 255, 'null' => true, 'default' => null])
            ->addColumn('refresh_token', 'string', ['limit' => 255, 'null' => true, 'default' => null])
            ->addForeignKey('admin_id', 'admins', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}
