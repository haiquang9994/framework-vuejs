<?php

namespace App\Service;


class PostService extends ApiService
{
    protected $model = '\App\Model\Post';

    public function boot()
    {
        $this->on('saving', function ($model) {
            $model->slug = $this->slugify($model, ['title']);
            $model->uniqueSlug();
        });
    }
}
