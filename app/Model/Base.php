<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

abstract class Base extends Model
{
    protected $raws = [];

    protected function getRaw(string $key, $default = null)
    {
        return $this->raws[$key] ?? $default;
    }

    public function fill(array $attributes)
    {
        $this->raws = $attributes;
        parent::fill($attributes);
    }

    public function uniqueSlug(string $slugName = 'slug')
    {
        $slug = $this->{$slugName};
        $count = 0;
        while (static::where($slugName, $slug)->where('id', '!=', $this->id)->count() > 0) {
            $slug = $this->{$slugName} . '-' . ++$count;
        }
        $this->{$slugName} = $slug;
    }
}
