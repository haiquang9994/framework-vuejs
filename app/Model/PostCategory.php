<?php

namespace App\Model;

class PostCategory extends Base
{
    protected $table = 'post_categories';

    protected $fillable = [
        'name', 'description', 'parent_id', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected function setActiveAttribute($value)
    {
        $this->attributes['active'] = boolval($value);
    }
}
