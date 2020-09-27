<?php

namespace App\Model;

class Post extends Base
{
    protected $table = 'post';

    protected $fillable = [
        'name', 'description', 'content', 'published_at', 'active', 'featured', 'image', 'category_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'active' => 'boolean',
        'featured' => 'boolean',
    ];

    protected function setActiveAttribute($value)
    {
        $this->attributes['active'] = boolval($value);
    }

    protected function setFeaturedAttribute($value)
    {
        $this->attributes['featured'] = boolval($value);
    }
}
