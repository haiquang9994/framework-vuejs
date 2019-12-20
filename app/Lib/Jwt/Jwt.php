<?php

namespace App\Lib\Jwt;

use DomainException;
use Firebase\JWT\JWT as FirebaseJWT;

class Jwt
{
    /**
     * @var FirebaseJWT
     */
    protected $jwt;

    public function __construct(FirebaseJWT $jwt)
    {
        $this->jwt = $jwt;
    }

    private function getPrivateKey() : string
    {
        return md5(env('APP_KEY', 'app_key'));
    }

    public function encode($data, string $key = null) : string
    {
        if ($key === null) {
            $key = $this->getPrivateKey();
        }
        return FirebaseJWT::encode($data, $key, 'HS256');
    }

    public function decode(string $token, string $key = null) : array
    {
        try {
            if ($key === null) {
                $key = $this->getPrivateKey();
            }
            return (array) FirebaseJWT::decode($token, $key, ['HS256']);
        } catch (DomainException $e) {
            return null;
        }
    }
}
