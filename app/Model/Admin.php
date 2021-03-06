<?php

namespace App\Model;

class Admin extends Base
{
    const ROLE_ADMIN = 'ADMIN';

    protected $table = 'admin';

    protected $fillable = [
        'email', 'password', 'fullname', 'roles',
    ];

    protected $casts = [
        'roles' => 'array',
    ];

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

    public function verify(string $password)
    {
        return password_verify($password, $this->password);
    }
}
