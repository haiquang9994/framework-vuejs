<?php

namespace App\Model;

class Token extends Base
{
    protected $table = 'token';

    protected $fillable = [
        'admin_id', 'token', 'last_time',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }
}
