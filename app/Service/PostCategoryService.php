<?php

namespace App\Service;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PostCategoryService extends ApiService
{
    protected $model = '\App\Model\PostCategory';

    public function item(Model $record): array
    {
        return [
            'id' => $record->id,
            'name' => $record->name,
            'slug' => $record->slug,
            'description' => $record->description,
            'active' => $record->active,
            'parent_id' => $record->parent_id,
            'created_at' => $this->formatIsoDatetime($record, 'created_at'),
            'updated_at' => $this->formatIsoDatetime($record, 'updated_at'),
        ];
    }

    protected function queryGet(): Builder
    {
        $query = parent::queryGet();
        if ($parent_id = $this->getApiParam('parent_id')) {
            $query->where('parent_id', $parent_id);
        } else {
            $query->whereNull('parent_id');
        }

        return $query;
    }

    public function boot()
    {
        $this->on('saving', function ($model) {
            $model->slug = $this->slugify($model, ['name']);
            $model->uniqueSlug();
        });
    }
}
