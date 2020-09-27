<?php

use Phinx\Migration\AbstractMigration;

class Setting extends AbstractMigration
{
    public function change()
    {
        $this->table('setting', ['collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('key', 'string', ['null' => true, 'default' => null])
            ->addColumn('value', 'text', ['null' => true, 'default' => null])
            ->create();
    }
}
