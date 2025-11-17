<?php

namespace App\Http\Controllers\Ticket;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Mews\Captcha\Facades\Captcha;
use App\Models\Ticket\ModelGetTicketLogin;

class TicketController extends Controller
{

    public function TicketLogin()
    {
        //dd($captcha);
        return view('ticket.ticket_login');
    }


    public function TicketLogout(Request $request)
    {
        Auth::guard('ticket')->logout();
        // Hapus semua data session
        $request->session()->flush();
        // dd($request->session()->all());
        return redirect(route('ticket.login'))->with([
            'message' => 'Logout Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function TicketDashboard()
    {
        // return view('vendor.index');
        return view('ticket.dashboard');
    }



    public function TicketLoginSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required|string',
        ], [
            'userid.required' => 'User ID Harus Diisi.',
        ]);

        if ($validator->fails()) {
            // Mengambil error pertama untuk ditampilkan dalam toast
            $errorMessage = $validator->errors()->first();

            // Redirect dengan pesan toast
            return redirect()->route('ticket.login')->with([
                'message' => $errorMessage,
                'alert-type' => 'error'
            ])->withErrors($validator)->withInput();
        }

        $data = [
            'userid' => $request->input('userid'),
            'password' => 1,
        ];



        try {
            $UserID = $request->input('userid');
            $Rolee = '';

            // Ambil data dari model
            $result = ModelGetTicketLogin::getRole($UserID, $Rolee);

            // Pastikan hasilnya tidak kosong dan dalam bentuk array
            if (!empty($result) && isset($result[0])) {
                $roleData = (array) $result[0]; // Ambil elemen pertama dan ubah ke array

                // Simpan ke session
                session()->put('RoleFacility', [
                    'Employee_ID'   => $roleData['Employee_ID'],
                    'Employee_Name' => $roleData['Employee_Name'],
                    'Kordinator'    => $roleData['Kordinator']
                ]);

                // Redirect ke halaman dashboard atau halaman lain setelah berhasil login

            } else {
                return redirect()->route('ticket.login')->with([
                    'message' => 'User ID tidak ditemukan. Silakan coba lagi.',
                    'alert-type' => 'error'
                ]);
            }
        } catch (\Exception $e) {
            // Tangani error dan log pesan error 
            return redirect()->route('ticket.login')->with([
                'message' => 'Error: ' . $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }



        if (Auth::guard('ticket')->attempt($data)) {

            $role = Auth::guard('ticket')->user()->role ?? null;

            if ($role === 'ticket') {

                return redirect()->route('ticket.dashboard')->with([
                    'message' => 'Login Successfully',
                    'alert-type' => 'success'
                ]);
            } else {
                return redirect()->route('ticket.dashboard')->with([
                    'message' => 'Login Successfully',
                    'alert-type' => 'success'
                ]);
            }
        } else {
            // Login gagal
            return redirect()->route('ticket.login')->with([
                'message' => 'Login Failed, Invalid Credential',
                'alert-type' => 'error'
            ]);
        }
    }
}
