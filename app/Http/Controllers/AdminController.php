<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Mews\Captcha\Facades\Captcha;

class AdminController extends Controller
{
    /**
     * Display admin login page
     */
    public function AdminLogin(Request $request)
    {
        $captcha = Captcha::create('default');
        $userid = $request->query('userid');
        $role = null;
        $facilities = [];

        if ($userid) {
            $data = $this->getRoleAndFacility($userid);
            if (!empty($data)) {
                $role = $data[0]->role ?? null;
                $facilities = collect($data)->pluck('facility')->unique()->values()->toArray();
            }
        }

        return view('admin.login', compact('captcha', 'role', 'facilities', 'userid'));
    }

    /**
     * Get role and facility from stored procedure
     */
    public function getRoleAndFacility($userid)
    {
        try {
            $result = DB::select('EXEC sp_get_facility_overtime @UserID = ?', [$userid]);
            return $result;
        } catch (\Exception $e) {
            \Log::error('SP error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get user role with multiple fallback methods
     */
    public function getUserRole($userid)
    {
        try {
            // Method 1: Try stored procedure first
            $data = $this->getRoleAndFacility($userid);
            if (!empty($data)) {
                return $data[0]->role ?? null;
            }
            
            // Method 2: Fallback to direct database query
            $userRole = DB::select('
                SELECT role 
                FROM arc_expediter.dbo.UserWebKLL 
                WHERE userid = ?', 
                [$userid]
            );
            
            if (!empty($userRole)) {
                return $userRole[0]->role;
            }

            // Method 3: Check in admin table
            $adminUser = Admin::where('userid', $userid)->first();
            if ($adminUser && $adminUser->role) {
                return $adminUser->role;
            }

            return null;

        } catch (\Exception $e) {
            \Log::error('Error getting user role: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get current logged in user's role
     */
    public function getCurrentUserRole()
    {
        if (Auth::guard('admin')->check()) {
            $userid = Auth::guard('admin')->user()->userid;
            return $this->getUserRole($userid);
        }
        return null;
    }

    /**
     * Get current logged in user's NIK
     */
    public function getCurrentUserNik()
    {
        if (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user()->userid;
        }
        return null;
    }

    /**
     * Get complete user information (role, NIK, name)
     */
    public function getCurrentUserInfo()
    {
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            return [
                'nik' => $user->userid,
                'name' => $user->name,
                'role' => $this->getUserRole($user->userid),
                'email' => $user->email,
                'phone' => $user->phone,
                'photo' => $user->photo
            ];
        }
        return null;
    }

    /**
     * Check if current user has specific role
     */
    public function hasRole($role)
    {
        $currentRole = $this->getCurrentUserRole();
        return $currentRole === $role;
    }

    /**
     * Check if current user is Driver
     */
    public function isDriver()
    {
        return $this->hasRole('Driver');
    }

    /**
     * Check if current user is Admin
     */
    public function isAdmin()
    {
        return $this->hasRole('Admin');
    }

    /**
     * Check if current user is Vendor
     */
    public function isVendor()
    {
        return $this->hasRole('Vendor');
    }

    /**
     * Admin logout
     */
    public function AdminLogout(Request $request)
    {
        // Clear user session data
        $request->session()->forget('user_role');
        $request->session()->forget('facility_info');
        
        Auth::guard('admin')->logout();
        $request->session()->flush();

        return redirect('/')->with([
            'message' => 'Logout Successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Admin dashboard
     */
    public function AdminDashboard()
    {
        $userInfo = $this->getCurrentUserInfo();
        $role = $userInfo['role'] ?? null;
        
        // Add additional data based on role
        $dashboardData = [
            'user_info' => $userInfo,
            'role' => $role,
            'welcome_message' => $this->getWelcomeMessage($role),
            'quick_actions' => $this->getQuickActions($role)
        ];

        return view('admin.index', compact('dashboardData', 'role'));
    }

    /**
     * Get welcome message based on role
     */
    private function getWelcomeMessage($role)
    {
        $messages = [
            'Admin' => 'Selamat datang di Dashboard Administrator',
            'Driver' => 'Selamat datang di Sistem Lembur Driver',
            'Vendor' => 'Selamat datang di Portal Vendor',
            'default' => 'Selamat datang di Sistem'
        ];

        return $messages[$role] ?? $messages['default'];
    }

    /**
     * Get quick actions based on role
     */
    private function getQuickActions($role)
    {
        $actions = [
            'Admin' => [
                ['name' => 'Manage Users', 'route' => 'admin.users', 'icon' => 'ri-user-settings-line'],
                ['name' => 'View Reports', 'route' => 'admin.reports', 'icon' => 'ri-file-chart-line'],
                ['name' => 'System Settings', 'route' => 'admin.settings', 'icon' => 'ri-settings-3-line']
            ],
            'Driver' => [
                ['name' => 'Data Lembur', 'route' => 'driver.overtime', 'icon' => 'ri-time-line'],
                ['name' => 'Profile', 'route' => 'admin.profile', 'icon' => 'ri-user-line'],
                ['name' => 'Change Password', 'route' => 'admin.change.password', 'icon' => 'ri-lock-password-line']
            ],
            'Vendor' => [
                ['name' => 'Vendor Dashboard', 'route' => 'vendor.dashboard', 'icon' => 'ri-dashboard-line'],
                ['name' => 'My Profile', 'route' => 'admin.profile', 'icon' => 'ri-user-line'],
                ['name' => 'Reports', 'route' => 'vendor.reports', 'icon' => 'ri-file-list-line']
            ],
            'default' => [
                ['name' => 'Profile', 'route' => 'admin.profile', 'icon' => 'ri-user-line'],
                ['name' => 'Change Password', 'route' => 'admin.change.password', 'icon' => 'ri-lock-password-line']
            ]
        ];

        return $actions[$role] ?? $actions['default'];
    }

    /**
     * Admin registration page
     */
    public function AdminRegister()
    {
        return view('admin.register');
    }

    /**
     * Admin registration submit
     */
    public function AdminRegisterSubmit(Request $request)
    {
        $request->validate([
            'userid' => ['required', 'string', 'alpha_num', 'size:6', 'unique:' . Admin::class],
            'name' => ['required', 'string', 'max:30', 'regex:/^[a-zA-Z\s]+$/'],
            'password' => ['required', 'string', 'min:8', 'max:30', 'confirmed', Rules\Password::defaults()],
            'role' => ['nullable', 'string', 'in:Admin,Driver,Vendor']
        ]);

        try {
            $admin = Admin::create([
                'userid' => $request->input('userid'),
                'name' => $request->input('name'),
                'password' => Hash::make($request->input('password')),
                'role' => $request->input('role', 'User'), // Default role
            ]);

            // Log the registration
            \Log::info('New admin registered', [
                'userid' => $admin->userid,
                'name' => $admin->name,
                'role' => $admin->role
            ]);

            return redirect()->back()->with('success', 'Registration successful. User can now login.');
        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Registration failed. Please try again.');
        }
    }

    /**
     * Get facilities by user ID
     */
    public function getFacilityByUserId(Request $request)
    {
        try {
            $facilities = DB::select(
                'EXEC [udsp_Get_Data] ?, ?, ?, ?, ?',
                ['Get Data Relasi', '', '', $request->input('userid'), '']
            );
            
            return response()->json([
                'success' => true,
                'facilities' => $facilities
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error getting facilities: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get facilities',
                'facilities' => []
            ], 500);
        }
    }

    /**
     * Admin login submit
     */
    public function AdminLoginSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required|string',
            'password' => 'required|string',
            'facility' => 'required|string',
            'captcha' => 'required|captcha'
        ], [
            'userid.required' => 'User ID Harus Diisi.',
            'password.required' => 'Password Harus Diisi.',
            'facility.required' => 'Facility Harus Dipilih.',
            'captcha.required' => 'Captcha Harus Diisi.',
            'captcha.captcha' => 'Captcha Tidak Sesuai Nilainya.'
        ]);

        if ($validator->fails()) {
            $errorMessage = $validator->errors()->first();
            return redirect()->route('admin.login')->with([
                'message' => $errorMessage,
                'alert-type' => 'error'
            ])->withErrors($validator)->withInput();
        }

        $credentials = [
            'userid' => $request->input('userid'),
            'password' => $request->input('password'),
        ];

        try {
            // Get facility information
            $results = DB::select(
                'EXEC [udsp_Get_Data] ?, ?, ?, ?',
                ['Get Infor Versi', '', '', $request->input('facility')]
            );

            if (empty($results)) {
                return redirect()->route('admin.login')->with([
                    'message' => 'Please Select Your Facility',
                    'alert-type' => 'error'
                ]);
            }

            $facilityInfo = [];
            foreach ($results as $result) {
                $facilityInfo[] = [
                    'Facility_ID' => $result->FACILITY_ID,
                    'Relasi' => $result->RELASI,
                    'Name' => $result->NAME,
                    'Type' => $result->TYPE,
                ];
            }
            session(['facility_info' => $facilityInfo]);

        } catch (\Exception $e) {
            \Log::error('Facility info error: ' . $e->getMessage());
            return redirect()->route('admin.login')->with([
                'message' => 'Error getting facility information',
                'alert-type' => 'error'
            ]);
        }

        // Login attempt
        if (Auth::guard('admin')->attempt($credentials)) {
            $userid = $request->input('userid');
            $role = $this->getUserRole($userid);
            
            // Store user information in session
            session([
                'user_role' => $role,
                'user_nik' => $userid,
                'user_name' => Auth::guard('admin')->user()->name
            ]);

            // Log login success
            \Log::info('User logged in', [
                'userid' => $userid,
                'role' => $role,
                'facility' => $request->input('facility')
            ]);

            // Redirect based on role
            return $this->redirectBasedOnRole($role, $userid);

        } else {
            // Log failed login attempt
            \Log::warning('Failed login attempt', [
                'userid' => $request->input('userid'),
                'ip' => $request->ip()
            ]);

            return redirect()->route('admin.login')->with([
                'message' => 'Login Failed, Invalid Credential',
                'alert-type' => 'error'
            ]);
        }
    }

    /**
     * Redirect user based on their role
     */
    private function redirectBasedOnRole($role, $userid)
    {
        $redirectData = [
            'message' => 'Login Successfully',
            'alert-type' => 'success'
        ];

        switch ($role) {
            case 'vendor':
                return redirect()->route('vendor.dashboard')->with($redirectData);
                
            case 'driver':
                $redirectData['message'] = 'Login as Driver Successfully';
                return redirect()->route('driver.overtime')->with($redirectData);
                
            case 'admin':
                $redirectData['message'] = 'Login as Admin Successfully';
                return redirect()->route('admin.dashboard')->with($redirectData);
                
            default:
                $redirectData['message'] = 'Login Successfully';
                return redirect()->route('admin.dashboard')->with($redirectData);
        }
    }

    /**
     * Admin profile page
     */
    public function AdminProfile()
    {
        $userInfo = $this->getCurrentUserInfo();
        $profileData = Admin::find(Auth::guard('admin')->id());
        $role = $userInfo['role'] ?? null;
        
        return view('admin.admin_profile', compact('profileData', 'role', 'userInfo'));
    }

    /**
     * Update admin profile
     */
    public function AdminProfileImage(Request $request)
    {
        $id = Auth::guard('admin')->id();
        $data = Admin::find($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'phone' => 'required|numeric|max:999999999999',
            'address' => 'required|max:200|regex:/^[\w\s.,]+$/',
            'photo' => 'nullable|image|mimes:jpg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data->name = $request->name;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $oldPhotoPath = $data->photo;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/admin_images'), $filename);
            $data->photo = $filename;

            if ($oldPhotoPath && $oldPhotoPath !== $filename) {
                $this->deleteOldImage($oldPhotoPath);
            }
        }

        $data->save();

        // Update session data
        session(['user_name' => $data->name]);

        $notification = [
            'message' => 'Profile Updated Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    /**
     * Delete old profile image
     */
    private function deleteOldImage(string $oldPhotoPath): void
    {
        $fullPath = public_path('upload/admin_images/' . $oldPhotoPath);
        if (file_exists($fullPath) && $oldPhotoPath !== 'default.jpg') {
            unlink($fullPath);
        }
    }

    /**
     * Change password page
     */
    public function AdminChangePassword()
    {
        $userInfo = $this->getCurrentUserInfo();
        $profileData = Admin::find(Auth::guard('admin')->id());
        $role = $userInfo['role'] ?? null;
        
        return view('admin.admin_change_password', compact('profileData', 'role', 'userInfo'));
    }

    /**
     * Update password
     */
    public function AdminUpdatePassword(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:8'
        ]);

        if (!Hash::check($request->old_password, $admin->password)) {
            $notification = [
                'message' => 'Old Password Does Not Match!',
                'alert-type' => 'error'
            ];
            return back()->with($notification);
        }

        Admin::whereId($admin->id)->update([
            'password' => Hash::make($request->input('new_password'))
        ]);

        // Log password change
        \Log::info('User changed password', [
            'userid' => $admin->userid,
            'name' => $admin->name
        ]);

        $notification = [
            'message' => 'Password Changed Successfully!',
            'alert-type' => 'success'
        ];
        
        return back()->with($notification);
    }

    /**
     * Get session user data (for AJAX calls)
     */
    public function getSessionUserData(Request $request)
    {
        try {
            $userInfo = $this->getCurrentUserInfo();
            
            if (!$userInfo) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'user' => $userInfo,
                'session' => [
                    'role' => session('user_role'),
                    'nik' => session('user_nik'),
                    'name' => session('user_name')
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting session user data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving user data'
            ], 500);
        }
    }
    /**
 * Get user information and facilities (for AJAX call)
 */
// public function getUserInfo(Request $request)
// {
//     try {
//         $request->validate([
//             'userid' => 'required|string'
//         ]);

//         $userid = $request->input('userid');
        
//         // Get user role
//         $role = $this->getUserRole($userid);
        
//         // Get user name from admin table
//         $adminUser = Admin::where('userid', $userid)->first();
//         $userName = $adminUser ? $adminUser->name : '';
        
//         // Get facilities
//         $facilities = DB::select(
//             'EXEC [udsp_Get_Data] ?, ?, ?, ?, ?',
//             ['Get Data Relasi', '', '', $userid, '']
//         );

//         return response()->json([
//             'success' => true,
//             'name' => $userName,
//             'role' => $role,
//             'facilities' => $facilities
//         ]);

//     } catch (\Exception $e) {
//         \Log::error('Error getting user info: ' . $e->getMessage());
        
//         return response()->json([
//             'success' => false,
//             'message' => 'Error retrieving user information'
//         ], 500);
//     }

// }

//kode baru get user info

public function getUserInfo(Request $request)
{
    try {
        $request->validate([
            'userid' => 'required|string'
        ]);

        $userid = $request->input('userid');

        // 1️⃣ Ambil role user
        $role = $this->getUserRole($userid);

        // 2️⃣ Ambil nama user dari tabel admin
        $adminUser = Admin::where('userid', $userid)->first();
        $userName = $adminUser ? $adminUser->name : '';

        // 3️⃣ Ambil facilities dari SP utama
        $facilities = DB::select(
            'EXEC [udsp_Get_Data] ?, ?, ?, ?, ?',
            ['Get Data Relasi', '', '', $userid, '']
        );

        // 4️⃣ Jika kosong, ambil dari sp_get_facility_overtime
        if (empty($facilities)) {
            $fallbackFacilities = DB::select(
                'EXEC [sp_get_facility_overtime] @UserID = ?', 
                [$userid]
            );

            // Ambil kolom 'facility' saja, ubah ke format konsisten
            $facilities = collect($fallbackFacilities)->map(function ($item) {
                return (object)[
                    'NAME' => $item->facility ?? null
                ];
            })->toArray();
        }

        // 5️⃣ Jika masih kosong juga
        if (empty($facilities)) {
            return response()->json([
                'success' => false,
                'message' => 'No facilities found for this user.'
            ]);
        }

        // 6️⃣ Kirim response sukses
        return response()->json([
            'success' => true,
            'name' => $userName,
            'role' => $role,
            'facilities' => $facilities
        ]);

    } catch (\Exception $e) {
        \Log::error('Error getting user info: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Error retrieving user information'
        ], 500);
    }
}



public function DriverOvertime(Request $request)
{
    try {
        // 1️⃣ Ambil NIK dari session login
        $nik = session('user_nik') ?? Auth::guard('admin')->user()->userid ?? null;

        if (!$nik) {
            \Log::warning("Session expired atau user belum login, tidak ada NIK ditemukan.");
            return redirect()->route('admin.login')->with([
                'message' => 'Session expired. Silakan login kembali.',
                'alert-type' => 'error'
            ]);
        }

        // 2️⃣ Tanggal default: H-1 dan H-0
        $startDate = $request->input('start_date_lembur', date('Y-m-d', strtotime('-1 day')));
        $endDate   = $request->input('end_date_lembur', date('Y-m-d'));

        \Log::info("Menjalankan SP GetLemburDataByNIK", [
            'nik' => $nik,
            'start' => $startDate,
            'end' => $endDate,
        ]);

        // 3️⃣ Jalankan stored procedure
        $dataLembur = [];
        try {
            $dataLembur = DB::select(
                "EXEC GetLemburDataByNIK @nik = ?, @start_date_lembur = ?, @end_date_lembur = ?",
                [$nik, $startDate, $endDate]
            );
        } catch (\Exception $ex) {
            \Log::error("Gagal eksekusi SP GetLemburDataByNIK: " . $ex->getMessage());
        }

        // 4️⃣ Log hasil
        \Log::info("Hasil SP GetLemburDataByNIK", [
            'count' => count($dataLembur),
            'sample' => $dataLembur ? (array) $dataLembur[0] : null
        ]);

        // 5️⃣ Ambil info user
        $userInfo = $this->getCurrentUserInfo();
        $role = $userInfo['role'] ?? 'driver';

        // 6️⃣ Kirim ke view (walaupun data kosong tetap aman)
        return view('admin.report.transport.overtime_driver', [
            'dataLembur' => $dataLembur,
            'userInfo' => $userInfo,
            'role' => $role,
            'defaultStartDate' => $startDate,
            'defaultEndDate' => $endDate,
            'nik' => $nik,
        ]);

    } catch (\Exception $e) {
        \Log::error('Error di DriverOvertime: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->route('admin.dashboard')->with([
            'message' => 'Terjadi kesalahan saat memuat data lembur.',
            'alert-type' => 'error'
        ]);
    }
}





}