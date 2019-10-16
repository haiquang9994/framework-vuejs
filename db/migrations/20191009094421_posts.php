<?php

use Phinx\Migration\AbstractMigration;

class Posts extends AbstractMigration
{
    public function change()
    {
        $this->table('posts', ['collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('title', 'string', ['null' => true, 'default' => null, 'limit' => 255])
            ->addColumn('slug', 'string', ['null' => true, 'default' => null, 'limit' => 255])
            ->addColumn('summary', 'text', ['null' => true, 'default' => null])
            ->addColumn('content', 'text', ['null' => true, 'default' => null])
            ->addColumn('published_at', 'timestamp', ['null' => true, 'default' => null])
            ->addColumn('active', 'boolean', ['null' => true, 'default' => null])
            ->addColumn('featured', 'boolean', ['null' => true, 'default' => null])
            ->addTimestamps()
            ->addIndex('slug', ['unique' => true])
            ->addIndex('active')
            ->addIndex('featured')
            ->create();
    }
}
