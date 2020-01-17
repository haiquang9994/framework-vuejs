<?php

use Phinx\Migration\AbstractMigration;

class PostCategories extends AbstractMigration
{
    public function change()
    {
        $this->table('post_categories', ['collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('name', 'string', ['null' => true, 'default' => null, 'limit' => 255])
            ->addColumn('slug', 'string', ['null' => true, 'default' => null, 'limit' => 255])
            ->addColumn('description', 'text', ['null' => true, 'default' => null])
            ->addColumn('active', 'boolean', ['null' => true, 'default' => null])
            ->addColumn('parent_id', 'integer', ['null' => true, 'default' => null])
            ->addTimestamps()
            ->addForeignKey('parent_id', 'post_categories', 'id', ['delete' => 'CASCADE'])
            ->addIndex('slug', ['unique' => true])
            ->addIndex('active')
            ->create();
    }
}
