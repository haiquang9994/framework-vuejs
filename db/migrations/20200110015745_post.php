<?php

use Phinx\Migration\AbstractMigration;

class Post extends AbstractMigration
{
    public function change()
    {
        $this->table('post', ['collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('name', 'string', ['null' => true, 'default' => null, 'limit' => 255])
            ->addColumn('slug', 'string', ['null' => true, 'default' => null, 'limit' => 255])
            ->addColumn('description', 'text', ['null' => true, 'default' => null])
            ->addColumn('content', 'text', ['null' => true, 'default' => null])
            ->addColumn('image', 'string', ['null' => true, 'default' => null])
            ->addColumn('published_at', 'timestamp', ['null' => true, 'default' => null])
            ->addColumn('active', 'boolean', ['null' => true, 'default' => null])
            ->addColumn('featured', 'boolean', ['null' => true, 'default' => null])
            ->addColumn('category_id', 'integer', ['null' => true, 'default' => null])
            ->addTimestamps()
            ->addForeignKey('category_id', 'post_category', 'id', ['delete' => 'CASCADE'])
            ->addIndex('slug', ['unique' => true])
            ->addIndex('active')
            ->addIndex('featured')
            ->create();
    }
}
