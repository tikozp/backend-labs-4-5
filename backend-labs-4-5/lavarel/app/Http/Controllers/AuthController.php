<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $keycloakUrl;
    protected $realm;
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $this->keycloakUrl = rtrim(env('KEYCLOAK_BASE_URL', 'http://localhost:7080'), '/');
        $this->realm = env('KEYCLOAK_REALM', 'lavarel-realm');
        $this->clientId = env('KEYCLOAK_CLIENT_ID', 'lavarel-cli');
        $this->clientSecret = env('KEYCLOAK_CLIENT_SECRET', '');
    }

    public function login(Request $request)
    {
        try {
            // Validate all required fields for the form
            $credentials = $request->validate([
                'grant_type' => 'required|string',
                'client_id' => 'required|string',
                'client_secret' => 'required|string',
                'username' => 'required|string',
                'password' => 'required|string'
            ]);

            $tokenUrl = "{$this->keycloakUrl}/realms/{$this->realm}/protocol/openid-connect/token";

            try {
                $response = Http::withOptions([
                    'verify' => false,
                ])
                ->asForm()  // This ensures x-www-form-urlencoded format
                ->post($tokenUrl, [
                    'grant_type' => $credentials['grant_type'],
                    'client_id' => $credentials['client_id'],
                    'client_secret' => $credentials['client_secret'],
                    'username' => $credentials['username'],
                    'password' => $credentials['password']
                ]);

                Log::info('Keycloak response', [
                    'status' => $response->status(),
                    'body' => $response->json() ?? $response->body()
                ]);

                if ($response->failed()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Authentication failed',
                        'debug' => [
                            'config' => [
                                'tokenUrl' => $tokenUrl
                            ],
                            'response' => [
                                'status' => $response->status(),
                                'body' => $response->json() ?? $response->body()
                            ]
                        ]
                    ], $response->status());
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful',
                    'data' => $response->json()
                ]);

            } catch (\Exception $e) {
                Log::error('HTTP request failed', [
                    'error' => $e->getMessage(),
                    'url' => $tokenUrl
                ]);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Connection to authentication server failed',
                    'debug' => [
                        'error' => $e->getMessage()
                    ]
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Authentication request failed',
                'debug' => [
                    'error' => $e->getMessage()
                ]
            ], 500);
        }
    }
}