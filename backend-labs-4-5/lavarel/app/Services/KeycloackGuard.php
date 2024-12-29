<?php

namespace App\Services;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\UserProvider;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class KeycloakGuard implements Guard
{
    use GuardHelpers;

    protected $request;
    protected $provider;
    protected $user;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->request = $request;
        $this->provider = $provider;
    }

    public function user()
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $token = $this->request->bearerToken();

        if (!$token) {
            return null;
        }

        try {
            $decoded = JWT::decode(
                $token,
                new Key(config('services.keycloak.public_key'), 'RS256')
            );

            // Find or create user based on Keycloak ID
            $this->user = $this->provider->retrieveById($decoded->sub);
            return $this->user;

        } catch (\Exception $e) {
            return null;
        }
    }

    public function validate(array $credentials = [])
    {
        return false;
    }
}