<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FrontendController extends Controller
{
    // dashboard methods start
    public function dashboard()
    {
        return view('modules.dashboard', ['title' => 'Dashboard']);
    }   
    // dashboard methods end
    
    // sws methods start
    public function swsInventory()
    {
        return view('modules.sws.inventory', ['title' => 'SWS Inventory Management']);
    }

    public function swsStorage()
    {
        return view('modules.sws.storage', ['title' => 'SWS Storage Management']);
    }

    public function swsRestock()
    {
        return view('modules.sws.restock', ['title' => 'SWS Restock Management']);
    }
    // sws methods end
    
    // psm methods start 
    public function psmVendorManagement()
    {
        return view('modules.psm.vendor-management');
    }

    public function psmVendorMarket()
    {
        return view('modules.psm.vendor-market');
    }

    public function psmOrderManagement()
    {
        return view('modules.psm.order-management');
    }

    public function psmBudgetApproval()
    {
        return view('modules.psm.budget-approval');
    }

    public function psmPlaceOrder()
    {
        return view('modules.psm.place-order');
    }

    public function psmReorderManagement()
    {
        return view('modules.psm.reorder-management');
    }

    public function psmProductsManagement()
    {
        return view('modules.psm.products-management');
    }

    public function psmShopManagement()
    {
        return view('modules.psm.shop-management');
    }
    // psm methods end
    
    // plt methods start
    public function pltProjects()
    {
        return view('modules.plt.projects');
    }

    public function pltDispatches()
    {
        return view('modules.plt.dispatches');
    }

    public function pltResources()
    {
        return view('modules.plt.resources');
    }

    public function pltAllocations()
    {
        return view('modules.plt.allocations');
    }

    public function pltMilestones()
    {
        return view('modules.plt.milestones');
    }

    public function pltTrackingLogs()
    {
        return view('modules.plt.tracking-logs');
    }
    // plt methods end
    
    // alms methods start
    public function almsRegistration()
    {
        return view('modules.alms.registration', ['title' => 'Asset Registration - ALMS']);
    }

    public function almsScheduling()
    {
        return view('modules.alms.scheduling', ['title' => 'Maintenance Scheduling - ALMS']);
    }

    public function almsTransfers()
    {
        return view('modules.alms.transfers', ['title' => 'Asset Transfers - ALMS']);
    }

    public function almsDisposals()
    {
        return view('modules.alms.disposals', ['title' => 'Disposal Management - ALMS']);
    }

    public function almsReports()
    {
        return view('modules.alms.reports', ['title' => 'Reports & Analytics - ALMS']);
    }
    // alms methods end
    
    // dtlr methods start
    public function dtlrUpload()
    {
        return view('modules.dtlr.upload');
    }

    public function dtlrDocuments()
    {
        return view('modules.dtlr.documents');
    }

    public function dtlrLogs()
    {
        return view('modules.dtlr.logs');
    }

    public function dtlrReviews()
    {
        return view('modules.dtlr.reviews');
    }
    // dtlr methods end

    public function processLogin(Request $request)
    {
        Log::info('Frontend login process started', $request->all());

        try {
            $response = Http::post('http://localhost:8001/api/auth/login', [
                'email' => $request->email,
                'password' => $request->password
            ]);

            Log::info('Gateway login response status: ' . $response->status());

            $data = $response->json();

            if ($response->successful() && $data['success']) {
                // Generate and send OTP
                Log::info('Generating OTP for user', ['user_id' => $data['user']['id'], 'email' => $request->email]);
                
                $otpResponse = Http::post('http://localhost:8001/api/auth/generate-otp', [
                    'email' => $request->email,
                    'user_id' => $data['user']['id']
                ]);

                Log::info('OTP generation response status: ' . $otpResponse->status());
                Log::info('OTP generation response data:', $otpResponse->json());

                $otpData = $otpResponse->json();

                if ($otpResponse->successful() && $otpData['success']) {
                    return response()->json([
                        'success' => true,
                        'requires_otp' => true,
                        'session_id' => $otpData['session_id'],
                        'email' => $request->email,
                        'message' => 'OTP sent to your email'
                    ]);
                } else {
                    Log::error('OTP generation failed', ['response' => $otpData]);
                    return response()->json([
                        'success' => false,
                        'message' => $otpData['message'] ?? 'Failed to send OTP. Please check the logs for OTP code.'
                    ], 400);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $data['message'] ?? 'Login failed'
                ], 401);
            }
        } catch (\Exception $e) {
            Log::error('Login process error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Service unavailable. Please try again later.'
            ], 503);
        }
    }

    public function showOtpVerification()
    {
        return view('auth.otp-verification');
    }

    public function verifyOtp(Request $request)
    {
        Log::info('Frontend OTP verification started', $request->all());

        try {
            $response = Http::post('http://localhost:8001/api/auth/verify-otp', [
                'session_id' => $request->session_id,
                'otp_code' => $request->otp_code,
                'email' => $request->email
            ]);

            Log::info('Gateway OTP verification response status: ' . $response->status());
            Log::info('Gateway OTP verification response data:', $response->json());

            $data = $response->json();

            if ($response->successful() && $data['success']) {
                $currentTime = time();
                
                // Set basic authentication cookies only (no session timeout cookies)
                setcookie('isAuthenticated', 'true', [
                    'expires' => 0, // Session cookie (browser session)
                    'path' => '/',
                    'secure' => false,
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]);
                
                setcookie('user', json_encode($data['user']), [
                    'expires' => time() + (86400 * 30), // 30 days
                    'path' => '/',
                    'secure' => false,
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]);

                Log::info('User logged in successfully - session timeout disabled', [
                    'user_id' => $data['user']['id']
                ]);

                return response()->json([
                    'success' => true,
                    'user' => $data['user'],
                    'redirect_to' => 'login-splash'
                ]);
            } else {
                Log::error('OTP verification failed', ['response' => $data]);
                return response()->json([
                    'success' => false,
                    'message' => $data['message'] ?? 'OTP verification failed'
                ], 401);
            }
        } catch (\Exception $e) {
            Log::error('OTP verification error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Service unavailable. Please try again later.'
            ], 503);
        }
    }

    public function resendOtp(Request $request)
    {
        Log::info('Frontend OTP resend requested', $request->all());

        try {
            $response = Http::post('http://localhost:8001/api/auth/resend-otp', [
                'session_id' => $request->session_id,
                'email' => $request->email
            ]);

            $data = $response->json();

            if ($response->successful() && $data['success']) {
                return response()->json([
                    'success' => true,
                    'session_id' => $data['session_id'],
                    'message' => 'New OTP sent successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $data['message'] ?? 'Failed to resend OTP'
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('OTP resend error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Service unavailable. Please try again later.'
            ], 503);
        }
    }

    public function updateProfile(Request $request)
    {
        Log::info('Frontend profile update started', $request->all());

        try {
            $response = Http::put('http://localhost:8001/api/profile/update', [
                'id' => $request->id,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'middlename' => $request->middlename,
                'Email' => $request->Email,
                'contactnum' => $request->contactnum,
                'sex' => $request->sex,
                'age' => $request->age,
                'birthdate' => $request->birthdate,
            ]);

            $data = $response->json();

            if ($response->successful() && $data['success']) {
                // Update cookies with new user data
                setcookie('user', json_encode($data['user']), time() + (86400 * 30), "/");

                return response()->json([
                    'success' => true,
                    'user' => $data['user']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $data['message'] ?? 'Profile update failed'
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Service unavailable. Please try again later.'
            ], 503);
        }
    }

    // Session timeout check
    public function checkSession(Request $request)
    {
        $lastActivity = $request->cookie('lastActivity');
        $currentTime = time();
        $timeout = 15 * 60; // 15 minutes in seconds

        if ($lastActivity && ($currentTime - $lastActivity) > $timeout) {
            return response()->json([
                'session_valid' => false,
                'message' => 'Session expired due to inactivity'
            ]);
        }

        // Update last activity
        setcookie('lastActivity', $currentTime, time() + (86400 * 30), "/");

        return response()->json([
            'session_valid' => true
        ]);
    }

    // PLT Proxy Methods
    public function pltProxyGet($endpoint, Request $request)
    {
        return $this->proxyRequest('GET', $endpoint, $request);
    }

    public function pltProxyPost($endpoint, Request $request)
    {
        return $this->proxyRequest('POST', $endpoint, $request);
    }

    public function pltProxyPut($endpoint, Request $request)
    {
        return $this->proxyRequest('PUT', $endpoint, $request);
    }

    public function pltProxyDelete($endpoint, Request $request)
    {
        return $this->proxyRequest('DELETE', $endpoint, $request);
    }

    private function proxyRequest($method, $endpoint, Request $request)
    {
        try {
            $url = 'http://localhost:8001/api/plt/' . $endpoint;
            
            Log::info("Proxying {$method} request to: {$url}", $request->all());

            $response = Http::timeout(30)->{$method}($url, $request->all());

            $data = $response->json();

            return response()->json($data, $response->status());

        } catch (\Exception $e) {
            Log::error('PLT Proxy error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'PLT Service unavailable. Please try again later.'
            ], 503);
        }
    }
}