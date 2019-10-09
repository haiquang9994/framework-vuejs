<?php

namespace App\Lib\Jwt;

use DomainException;
use Firebase\JWT\JWT as FirebaseJWT;

class Jwt
{
    protected $jwt;
    protected $privateKey;

    public function __construct(FirebaseJWT $jwt, string $privateKey = 'secret')
    {
        $this->jwt = $jwt;
        $this->privateKey = $privateKey;
    }

    public function encode($data, string $key = null)
    {
        if ($key === null) {
            $key = $this->privateKey;
        }
        return FirebaseJWT::encode($data, $key, 'HS256');
    }

    public function decode(string $token, string $key)
    {
        try {
            if ($key === null) {
                $key = $this->privateKey;
            }
            return FirebaseJWT::decode($token, $key, ['HS256']);
        } catch (DomainException $e) {
            return null;
        }
    }
}
