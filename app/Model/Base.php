<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

abstract class Base extends Model
{
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
