<?php

namespace App\Model;

class Admin extends Base
{
    const ROLE_ADMIN = 'ADMIN';

    protected $fillable = [
        'email', 'password', 'fullname', 'roles',
    ];

    protected $casts = [
        'roles' => 'array',
    ];

    protected $table = 'admins';

    protected $hidden = [
        'password',
    ];

    protected function setPasswordAttribute(string $password)
    {
        $this->attributes['password'] = password_hash($password, PASSWORD_BCRYPT);
    }

    public function getRoles()
    {
        return $this->roles;
    }
}
