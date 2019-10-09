<?php

namespace App\Model;

class Token extends Base
{
    protected $table = 'tokens';

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }
}
