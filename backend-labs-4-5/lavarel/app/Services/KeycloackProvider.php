<?php

namespace App\Services;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;

class KeycloakProvider extends AbstractProvider
{
    protected $baseUrl;

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            $this->baseUrl . '/auth',
            $state
        );
    }

    protected function getTokenUrl()
    {
        return $this->baseUrl . '/token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(
            $this->baseUrl . '/userinfo',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]
        );

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['sub'],
            'name' => $user['name'] ?? null,
            'email' => $user['email'] ?? null,
        ]);
    }
}