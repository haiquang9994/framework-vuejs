<?php

namespace App\Service;

use Illuminate\Database\Eloquent\Model;

class PostService extends ApiService
{
    protected $model = '\App\Model\Post';

    public function item(Model $record) : array
    {
        return [
            'id' => $record->id,
            'name' => $record->name,
            'slug' => $record->slug,
            'description' => $record->description,
            'content' => $record->content,
            'published_at' => $this->formatIsoDatetime($record, 'published_at'),
            'active' => $record->active,
            'featured' => $record->featured,
            'category_id' => $record->category_id,
            'image' => $record->image,
            'thumb' => $record->image,
            'created_at' => $this->formatIsoDatetime($record, 'created_at'),
            'updated_at' => $this->formatIsoDatetime($record, 'updated_at'),
        ];
    }

    public function boot()
    {
        $this->on('saving', function ($model) {
            $model->slug = $this->slugify($model, ['name']);
            $model->uniqueSlug();
        });
    }
}
