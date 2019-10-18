<?php

namespace App\Model;

class Post extends Base
{
    protected $table = 'posts';

    protected $fillable = [
        'title', 'summary', 'content', 'published_at', 'active', 'featured', 'image',
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
