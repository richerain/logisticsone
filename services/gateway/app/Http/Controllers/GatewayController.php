<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class GatewayController extends Controller
{
    public function login(Request $request)
    {
        Log::info('Gateway login hit', $request->all());

        $client = new Client();

        try {
            $response = $client->post('http://localhost:8002/api/auth/login', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => $request->all(),
                'timeout' => 30,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            return response()->json($body, $response->getStatusCode());

        } catch (RequestException $e) {
            Log::error('Gateway proxy error: ' . $e->getMessage());

            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $responseBody = $e->getResponse()->getBody()->getContents();
                $body = json_decode($responseBody, true) ?? ['success' => false, 'message' => 'Service error'];

                return response()->json($body, $statusCode);
            }

            return response()->json([
                'success' => false,
                'message' => 'Service unavailable'
            ], 503);
        }
    }

    public function updateProfile(Request $request)
    {
        Log::info('Gateway profile update hit', $request->all());

        $client = new Client();

        try {
            $response = $client->put('http://localhost:8002/api/profile/update', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => $request->all(),
                'timeout' => 30,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            return response()->json($body, $response->getStatusCode());

        } catch (RequestException $e) {
            Log::error('Gateway profile update error: ' . $e->getMessage());

            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $responseBody = $e->getResponse()->getBody()->getContents();
                $body = json_decode($responseBody, true) ?? ['success' => false, 'message' => 'Service error'];

                return response()->json($body, $statusCode);
            }

            return response()->json([
                'success' => false,
                'message' => 'Service unavailable'
            ], 503);
        }
    }

    public function uploadProfilePicture(Request $request)
    {
        Log::info('Gateway profile picture upload hit');

        $client = new Client();

        try {
            $response = $client->post('http://localhost:8002/api/profile/upload-picture', [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'multipart' => [
                    [
                        'name' => 'id',
                        'contents' => $request->id
                    ],
                    [
                        'name' => 'profile_picture',
                        'contents' => fopen($request->file('profile_picture')->getPathname(), 'r'),
                        'filename' => $request->file('profile_picture')->getClientOriginalName(),
                        'headers' => [
                            'Content-Type' => $request->file('profile_picture')->getMimeType()
                        ]
                    ]
                ],
                'timeout' => 30,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            return response()->json($body, $response->getStatusCode());

        } catch (RequestException $e) {
            Log::error('Gateway profile picture upload error: ' . $e->getMessage());

            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $responseBody = $e->getResponse()->getBody()->getContents();
                $body = json_decode($responseBody, true) ?? ['success' => false, 'message' => 'Service error'];

                return response()->json($body, $statusCode);
            }

            return response()->json([
                'success' => false,
                'message' => 'Service unavailable'
            ], 503);
        }
    }

    // Proxy methods for SWS module
    public function proxyGet(Request $request, $url)
    {
        return $this->proxyRequest($request, $url, 'GET');
    }

    public function proxyPost(Request $request, $url)
    {
        return $this->proxyRequest($request, $url, 'POST');
    }

    public function proxyPut(Request $request, $url)
    {
        return $this->proxyRequest($request, $url, 'PUT');
    }

    public function proxyDelete(Request $request, $url)
    {
        return $this->proxyRequest($request, $url, 'DELETE');
    }

    private function proxyRequest(Request $request, $url, $method = 'GET')
    {
        $client = new Client();

        try {
            $options = [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'timeout' => 30,
            ];

            if ($method !== 'GET') {
                $options['json'] = $request->all();
            } else {
                $options['query'] = $request->query();
            }

            $response = $client->request($method, $url, $options);

            $body = json_decode($response->getBody()->getContents(), true);

            return response()->json($body, $response->getStatusCode());

        } catch (RequestException $e) {
            Log::error('Gateway proxy error: ' . $e->getMessage());

            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $responseBody = $e->getResponse()->getBody()->getContents();
                $body = json_decode($responseBody, true) ?? ['success' => false, 'message' => 'Service error'];

                return response()->json($body, $statusCode);
            }

            return response()->json([
                'success' => false,
                'message' => 'Service unavailable'
            ], 503);
        }
    }
}