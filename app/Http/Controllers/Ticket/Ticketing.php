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
use SimpleSoftwareIO\QrCode\Facades\QrCode;



class Ticketing extends Controller
{

    public function getTicketData()
    {
        $EmployeeID = session('RoleFacility.Employee_ID', '');
        $Namee = session('RoleFacility.Employee_Name', '');
        $Role = session('RoleFacility.Kordinator', '');
        $Rolee = '';

        // Ambil data tiket dari model
        $tickets = ModelGetTicketLogin::getTicket($EmployeeID, $Rolee);

        if (!$tickets) {
            return response()->json(['error' => 'No ticket found'], 404);
        }

        $formattedTickets = [];

        foreach ($tickets as $ticket) {
            $formattedTickets[] = [
                'ticket_id' => $ticket->KodeTiket,
                'nik_name' => $ticket->NIK . ' - ' . $ticket->NamaKaryawan,
                'tujuan' => $ticket->Tujuan,
                'nomor_bus' => $ticket->NoBus,
                'tanggal' => $ticket->Tanggal,
                'jam_berangkat' => $ticket->WaktuBerangkat,
                'no_kursi' => $ticket->NoKursi,
                'status' => $ticket->Status,
                'totaltiket' => $ticket->TotalTiket,
                'keberangkatan' => $ticket->Keberangkatan,
                'barcode' => QrCode::size(180)->generate($ticket->KodeTiket),
            ];
        }

        // Cek apakah request dari AJAX dan hanya ingin update status
        if (request()->ajax()) {
            if (request()->has('status_only') && request()->status_only == 'true') {
                return response()->json(array_map(function ($ticket) {
                    return [
                        'ticket_id' => $ticket['ticket_id'],
                        'status' => $ticket['status']
                    ];
                }, $formattedTickets));
            }

            return response()->json($formattedTickets);
        }

        // Jika request bukan AJAX, kembalikan semua data ke view
        return view('ticket.ticket.ticket_view', compact('formattedTickets'));
    }




    public function dashboardTicketData()
    {
        try {
            $EmployeeID = session('RoleFacility.Employee_ID', '');
            $Namee = session('RoleFacility.Employee_Name', '');
            $Role = session('RoleFacility.Kordinator', '');
            $Rolee = '';

            // Ambil data tiket awal dari model
            $keberangkatanData = ModelGetTicketLogin::getKeberangkatan();

            // Konversi keberangkatan menjadi array string
            $keberangkatan = [];

            foreach ($keberangkatanData as $data) {
                if (isset($data->Keberangkatan)) { // Pastikan properti ada
                    $keberangkatan[] = $data->Keberangkatan;
                }
            }

            $ticketdashboard = ModelGetTicketLogin::getDashboard($EmployeeID, $Rolee);

            // Simpan data awal ke session
            session(['ticketdashboard' => $ticketdashboard]);
            session(['keberangkatan' => $keberangkatan]);

            return view('ticket.ticket.dashboard_ticket_view', compact('ticketdashboard', 'keberangkatan'));
        } catch (\Exception $e) {
            // Redirect kembali dengan pesan error
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }


    public function dashboardTicketData1(Request $request)
    {

        try {
            $Rolee = '';

            // Validasi input harus diisi
            $request->validate([
                'keberangkatan' => 'required',
                'bus' => 'required',
                'status2' => 'required'
            ], [
                'keberangkatan.required' => 'Keberangkatan wajib diisi.',
                'bus.required' => 'Bus wajib diisi.',
                'status2.required' => 'Status wajib diisi.'
            ]);

            // Ambil data dari form
            $keberangkatan1 = $request->input('keberangkatan', '');
            $bus = $request->input('bus', '');
            $status1 = $request->input('status2', '');

            // Ambil data hasil filter dari model
            $ticketdashboard1 = ModelGetTicketLogin::getDashboard1($keberangkatan1, $bus, $status1) ?? [];

            // Jika hasil pencarian kosong, gunakan data dari session
            $ticketdashboard = session('ticketdashboard', []);
            $keberangkatan = session('keberangkatan', []);

            return view('ticket.ticket.dashboard_ticket_view', compact('ticketdashboard', 'ticketdashboard1', 'keberangkatan', 'bus', 'status1'));
        } catch (\Exception $e) {
            // Redirect kembali ke halaman sebelumnya dengan pesan error
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }


    public function getBusByKeberangkatan(Request $request)
    {
        $keberangkatan = $request->keberangkatan;
        $buses = ModelGetTicketLogin::getBus($keberangkatan);

        return response()->json($buses); // Kirim data ke frontend
    }

    public function insertTicket(Request $request)
    {
        $barcode = $request->input('barcode');
        $employeeID = session('RoleFacility.Employee_ID', '');

        if (!$barcode) {
            return response()->json(['success' => false, 'message' => 'Barcode is empty!'], 400);
        }

        $statusticket = ModelGetTicketLogin::getStatusTicket($barcode);
        // Ambil nilai status dari array
        $status = $statusticket[0]->Status ?? null;
        if ($status == 1) {
            return response()->json(['success' => false, 'message' => 'Status Ticket Sudah Closed']);
        }
        // Jika status tiket 0, lanjutkan proses insert
        $ticket = ModelGetTicketLogin::postTicket($employeeID, $barcode);

        return response()->json(['success' => true, 'message' => 'Ticket inserted successfully!']);
    }
}
