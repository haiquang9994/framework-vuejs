<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

abstract class Base extends Model
{
    public function uniqueSlug()
    {
        $slug = $this->slug;
        $count = 0;
        while (static::where('slug', $slug)->where('id', '!=', $this->id)->count() > 0) {
            $slug = $this->slug . '-' . ++$count;
        }
        $this->slug = $slug;
    }
}
