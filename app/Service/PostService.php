<?php

namespace App\Service;

use Illuminate\Database\Eloquent\Model;

class PostService extends ApiService
{
    protected $model = '\App\Model\Post';

    public function item(Model $record): array
    {
        return [
            'id' => $record->id,
            'title' => $record->title,
            'slug' => $record->slug,
            'summary' => $record->summary,
            'content' => $record->content,
            'published_at' => $this->formatIsoDatetime($record, 'published_at'),
            'active' => $record->active,
            'featured' => $record->featured,
            'created_at' => $this->formatIsoDatetime($record, 'created_at'),
            'updated_at' => $this->formatIsoDatetime($record, 'updated_at'),
        ];
    }

    public function boot()
    {
        $this->on('saving', function ($model) {
            $model->slug = $this->slugify($model, ['title']);
            $model->uniqueSlug();
        });
    }
}
