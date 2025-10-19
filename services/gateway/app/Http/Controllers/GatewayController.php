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

    // Enhanced OTP methods
    public function generateOtp(Request $request)
    {
        Log::info('Gateway generate OTP hit', $request->all());
        return $this->proxyRequest($request, 'http://localhost:8002/api/auth/generate-otp', 'POST');
    }

    public function verifyOtp(Request $request)
    {
        Log::info('Gateway verify OTP hit', $request->all());
        return $this->proxyRequest($request, 'http://localhost:8002/api/auth/verify-otp', 'POST');
    }

    public function resendOtp(Request $request)
    {
        Log::info('Gateway resend OTP hit', $request->all());
        return $this->proxyRequest($request, 'http://localhost:8002/api/auth/resend-otp', 'POST');
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

    // Enhanced proxy methods with better error handling
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
        Log::info("Gateway {$method} request to: {$url}", $request->all());

        $client = new Client();

        try {
            $options = [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'timeout' => 30,
                'connect_timeout' => 10,
            ];

            if ($method !== 'GET') {
                $options['json'] = $request->all();
            } else {
                $options['query'] = $request->query();
            }

            $response = $client->request($method, $url, $options);

            $body = json_decode($response->getBody()->getContents(), true);

            Log::info("Gateway {$method} response from {$url}", [
                'status' => $response->getStatusCode(),
                'body' => $body
            ]);

            return response()->json($body, $response->getStatusCode());

        } catch (RequestException $e) {
            Log::error("Gateway {$method} error for {$url}: " . $e->getMessage());

            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $responseBody = $e->getResponse()->getBody()->getContents();
                Log::error("Service response: {$responseBody}");
                
                $body = json_decode($responseBody, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Service error: ' . $responseBody
                    ], $statusCode);
                }
                
                return response()->json($body ?? ['success' => false, 'message' => 'Service error'], $statusCode);
            }

            // Check if it's a connection error
            if (str_contains($e->getMessage(), 'cURL error 7') || 
                str_contains($e->getMessage(), 'Failed to connect')) {
                Log::error("Connection failed to service: {$url}");
                return response()->json([
                    'success' => false,
                    'message' => 'Service temporarily unavailable. Please try again.'
                ], 503);
            }

            return response()->json([
                'success' => false,
                'message' => 'Service unavailable: ' . $e->getMessage()
            ], 503);
        } catch (\Exception $e) {
            Log::error("Gateway general error for {$url}: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Unexpected error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Dedicated method for DTLR document uploads
    public function uploadDocument(Request $request)
    {
        Log::info('Gateway document upload hit', [
            'has_file' => $request->hasFile('document_file'),
            'all_data' => $request->all()
        ]);

        $client = new Client();

        try {
            // Build multipart form data
            $multipart = [];

            // Add all form fields
            foreach ($request->all() as $name => $value) {
                if ($name !== 'document_file') {
                    $multipart[] = [
                        'name' => $name,
                        'contents' => $value
                    ];
                }
            }

            // Add the document file if present
            if ($request->hasFile('document_file')) {
                $file = $request->file('document_file');
                $multipart[] = [
                    'name' => 'document_file',
                    'contents' => fopen($file->getPathname(), 'r'),
                    'filename' => $file->getClientOriginalName(),
                    'headers' => [
                        'Content-Type' => $file->getMimeType()
                    ]
                ];
                Log::info('Added document file to multipart', [
                    'filename' => $file->getClientOriginalName(),
                    'size' => $file->getSize()
                ]);
            } else {
                Log::warning('No document file found in request');
            }

            $response = $client->post('http://localhost:8006/api/documents', [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'multipart' => $multipart,
                'timeout' => 30,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            Log::info('Document upload response', [
                'status' => $response->getStatusCode(),
                'success' => $body['success'] ?? false
            ]);

            return response()->json($body, $response->getStatusCode());

        } catch (RequestException $e) {
            Log::error('Gateway document upload error: ' . $e->getMessage());

            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $responseBody = $e->getResponse()->getBody()->getContents();
                Log::error('DTLR service response: ' . $responseBody);
                
                $body = json_decode($responseBody, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return response()->json([
                        'success' => false,
                        'message' => 'DTLR Service error: ' . $responseBody
                    ], $statusCode);
                }
                
                return response()->json($body ?? ['success' => false, 'message' => 'DTLR Service error'], $statusCode);
            }

            return response()->json([
                'success' => false,
                'message' => 'DTLR Service unavailable: ' . $e->getMessage()
            ], 503);
        }
    }

    // method for PSM product uploads
    public function proxyUpload(Request $request, $url)
    {
        $client = new Client();
        Log::info('Gateway upload called for URL: ' . $url);

        try {
            $options = [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'timeout' => 30,
            ];

            // Determine the method from the URL structure
            $method = (strpos($url, '/products/') !== false && !str_ends_with($url, '/products')) ? 'PUT' : 'POST';

            // Handle multipart form data for file uploads
            if ($request->hasFile('prod_img')) {
                Log::info('File detected in upload request, method: ' . $method);
                $options['multipart'] = [];
                
                // Add all form fields except the file and _method
                foreach ($request->all() as $name => $value) {
                    if ($name !== 'prod_img' && $name !== '_method') {
                        $options['multipart'][] = [
                            'name' => $name,
                            'contents' => $value
                        ];
                    }
                }
                
                // Add the file
                $options['multipart'][] = [
                    'name' => 'prod_img',
                    'contents' => fopen($request->file('prod_img')->getPathname(), 'r'),
                    'filename' => $request->file('prod_img')->getClientOriginalName(),
                    'headers' => [
                        'Content-Type' => $request->file('prod_img')->getMimeType()
                    ]
                ];

                Log::info('Sending multipart ' . $method . ' request with file');
            } else {
                Log::info('No file detected, sending JSON ' . $method . ' request');
                $options['json'] = $request->except(['_method']);
            }

            $response = $client->request($method, $url, $options);
            $body = json_decode($response->getBody()->getContents(), true);

            Log::info('Upload response received', ['status' => $response->getStatusCode(), 'body' => $body]);

            return response()->json($body, $response->getStatusCode());

        } catch (RequestException $e) {
            Log::error('Gateway upload error: ' . $e->getMessage());

            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $responseBody = $e->getResponse()->getBody()->getContents();
                Log::error('Service response: ' . $responseBody);
                
                // Try to parse as JSON, if fails return raw response
                $body = json_decode($responseBody, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Service error: ' . $responseBody
                    ], $statusCode);
                }
                
                return response()->json($body ?? ['success' => false, 'message' => 'Service error'], $statusCode);
            }

            return response()->json([
                'success' => false,
                'message' => 'Service unavailable: ' . $e->getMessage()
            ], 503);
        }
    }
}