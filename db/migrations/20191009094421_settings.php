<?php

use Phinx\Migration\AbstractMigration;

class Settings extends AbstractMigration
{
    public function change()
    {
        $this->table('settings', ['collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('key', 'string', ['null' => true, 'default' => null])
            ->addColumn('value', 'text', ['null' => true, 'default' => null])
            ->create();
    }
}
