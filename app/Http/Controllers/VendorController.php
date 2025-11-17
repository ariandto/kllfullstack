<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Vendor;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Mews\Captcha\Facades\Captcha;

class VendorController extends Controller
{
    public function VendorLogout(Request $request)
    {
        Auth::guard('admin')->logout();
        // Hapus semua data session
        $request->session()->flush();
        // dd($request->session()->all());
        return redirect('/')->with([
            'message' => 'Logout Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function VendorDashboard()
    {
        // return view('vendor.index');
        return view('vendor.vendor_dashboard');
    }

    public function VendorProfile()
    {
        $id = Auth::guard('admin')->id();
        $profileData = Admin::find($id);
        return view('vendor.vendor_profile', compact('profileData'));
    }

    public function VendorProfileImage(Request $request)
    {
        $id = Auth::guard('admin')->id();
        $data = Admin::find($id);

        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z\s]+$/', // Nama harus menggunakan huruf
            'phone' => 'required|numeric|max:999999999999', // Nomor telepon maksimal 12 digit
            'address' => 'required|max:200|regex:/^[\w\s.,]+$/', // Alamat maksimal 200 karakter, hanya huruf, spasi, titik, dan koma
            'photo' => 'nullable|image|mimes:jpg,png|max:2048', // Foto harus jpg atau png, ukuran maksimal 2MB
        ]);


        if ($validator->fails()) {
            //dd($validator->errors()); // Tambahkan ini untuk melihat kesalahan
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Jika validasi berhasil, lanjutkan dengan penyimpanan data
        $data->name = $request->name;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $oldPhotoPath = $data->photo;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/vendor_images'), $filename);
            $data->photo = $filename;

            if ($oldPhotoPath && $oldPhotoPath !== $filename) {
                $this->deleteOldImage($oldPhotoPath);
            }
        }

        $data->save();

        $notification = array(
            'message' => 'Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    private function deleteOldImage(string  $oldPhotoPath): void
    {
        $fullPath = public_path('upload/vendor_images/' . $oldPhotoPath);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    public function VendorChangePassword()
    {
        $id = Auth::guard('admin')->id();
        $profileData = Admin::find($id);
        return view('vendor.vendor_change_password', compact('profileData'));
    }

    public function VendorUpdatePassword(Request $request)
    {
        $vendor = Auth::guard('admin')->user();
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);

        if (!Hash::check($request->old_password, $vendor->password)) {
            $notification = array(
                'message' => 'Old Password Does Not Match !',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }

        //Update New Password Disini
        // dd($request);
        Admin::whereId($vendor->id)->update([
            'password' => Hash::make($request->input('new_password'))
        ]);

        $notification = array(
            'message' => 'Password Changed Successfully !',
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }


    // public function VendorRegister()
    // {
    //     return view('vendor.vendor_register');
    // } 

    // public function VendorRegisterSubmit(Request $request)
    // {
    //     $request->validate([  
    //         'userid' => [ 'required', 'string', 'alpha_num', 'size:6',  'unique:'.Vendor::class],  
    //         'name' => [  'required', 'string', 'max:30', 'regex:/^[a-zA-Z\s]+$/' ],
    //         'password' => [ 'required', 'string', 'min:8', 'max:30', 'password' => ['required', Rules\Password::defaults()], ]
    //     ]); 

    //     try {
    //         // Simpan data baru menggunakan create
    //         $UserWebKLL = Vendor::create([
    //             'userid' => $request->input('userid'),
    //             'name' => $request->input('name'),
    //             'password' => Hash::make($request->input('password')), // Hash password sebelum disimpan
    //         ]);

    //         // Redirect ke halaman login dengan pesan sukses
    //         return redirect()->back()->with('success', 'Registration successful. User Already Login.'); 

    //     } catch (\Exception $e) {
    //         error_log('Registration error: ' . $e->getMessage()); 
    //         return redirect()->back()->with('error', 'Registration failed. Please try again.');
    //     }
    // } 

    // public function getFacilityByUserId(Request $request)
    // {
    //     $facilities = $request->input('userid');
    //     $facilities = DB::select(
    //         'EXEC [udsp_Get_Data] ?, ?, ?, ?, ?',
    //         ['Get Data Relasi Vendor', '', '', $request->input('userid'), '' ]
    //     );
    //     return response()->json([
    //         'facilities' => $facilities
    //     ]);
    // } 

    // public function VendorLoginSubmit(Request $request) { 
    //     $validator = Validator::make($request->all(), [
    //         'userid' => 'required|string',
    //         'password' => 'required|string',
    //         'facility' => 'required|string',
    //         'captcha' => 'required|captcha'
    //     ], 

    //     [
    //         'userid.required' => 'User ID Harus Diisi.',
    //         'password.required' => 'Password Harus Diisi.',
    //         'facility.required' => 'Facility Harus Dipilih.',
    //         'captcha.required' => 'Captcha Harus Diisi.',
    //         'captcha.captcha' => 'Captcha Tidak Sesuai Nilainya.'
    //     ]);

    //     if ($validator->fails()) {
    //         // Mengambil error pertama untuk ditampilkan dalam toast
    //         $errorMessage = $validator->errors()->first();

    //         // Redirect dengan pesan toast
    //         return redirect()->route('vendor.login')->with([
    //             'message' => $errorMessage,
    //             'alert-type' => 'error'
    //         ])->withErrors($validator)->withInput();
    //     }          

    //     $data = [
    //         'userid' => $request->input('userid'),
    //         'password' => $request->input('password'), 
    //     ];

    //     try {
    //         // Execute stored procedure
    //         $results = DB::select(
    //             'EXEC [udsp_Get_Data] ?, ?, ?, ?',
    //             ['Get Infor Versi Vendor', '', '', $request->input('facility')]
    //         );

    //         if (empty($results)) {
    //         // No data returned, show error
    //             return redirect()->route('vendor.login')->with([
    //                 'message' => 'Please Select Your Facility',
    //                 'alert-type' => 'error'
    //             ]);
    //         }

    //         // Store the facility info in session if the stored procedure returns data
    //         $facilityInfo = [];
    //         foreach ($results as $result) {
    //             $facilityInfo[] = [
    //                 'Facility_ID' => $result->FACILITY_ID,
    //                 'Relasi' => $result->RELASI,
    //                 'Name' => $result->NAME,
    //             ];
    //         }
    //         session(['facility_info' => $facilityInfo]);

    //     } catch (\Exception $e) {
    //         // Catch any database errors
    //         return redirect()->route('vendor.login')->with([
    //             'message' => 'Error: ' . $e->getMessage(),
    //             'alert-type' => 'error'
    //         ]);
    //     }

    //     // Attempt login
    //     if (Auth::guard('vendor')->attempt($data)) {
    //         // Successful login
    //         return redirect()->route('vendor.dashboard')->with([
    //             'message' => 'Login Successfully',
    //             'alert-type' => 'success'
    //         ]);
    //     } else {
    //         // Login failed
    //         return redirect()->route('vendor.login')->with([
    //             'message' => 'Login Failed, Invalid Credential',
    //             'alert-type' => 'error'
    //         ]);
    //     }
    // }

    // public function VendorLogin()
    // {
    // // Buat CAPTCHA
    // // Buat CAPTCHA dan pastikan kita mendapatkan array, bukan Response 
    // $captcha = Captcha::create('default'); 
    // //dd($captcha);
    // return view('vendor.vendor_login');
    // }





}
