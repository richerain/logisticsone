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
    public function pltShipment()
    {
        return view('modules.plt.shipment', ['title' => 'PLT Shipment']);
    }

    public function pltRoute()
    {
        return view('modules.plt.route', ['title' => 'PLT Route']);
    }
    // plt methods end
    // alms methods start
    public function almsRegistration()
    {
        return view('modules.alms.registration', ['title' => 'ALMS Registration']);
    }

    public function almsScheduling()
    {
        return view('modules.alms.scheduling', ['title' => 'ALMS Scheduling']);
    }
    // alms methods end
    // dtlr methods start
    public function dtlrUpload()
    {
        return view('modules.dtlr.upload', ['title' => 'DTLR Upload']);
    }

    public function dtlrLogs()
    {
        return view('modules.dtlr.logs', ['title' => 'DTLR Logs']);
    }
    // dtlr methods end
    // usermngt methods start
    public function userManagement()
    {
        return view('modules.user-management', ['title' => 'User Management']);
    }
    // usermngt methods end

    public function processLogin(Request $request)
    {
        Log::info('Frontend login process started', $request->all());

        try {
            $response = Http::post('http://localhost:8001/api/auth/login', [
                'email' => $request->email,
                'password' => $request->password
            ]);

            $data = $response->json();

            if ($response->successful() && $data['success']) {
                // Set authentication cookies
                setcookie('isAuthenticated', 'true', time() + (86400 * 30), "/");
                setcookie('user', json_encode($data['user']), time() + (86400 * 30), "/");

                return response()->json([
                    'success' => true,
                    'user' => $data['user']
                ]);
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
}