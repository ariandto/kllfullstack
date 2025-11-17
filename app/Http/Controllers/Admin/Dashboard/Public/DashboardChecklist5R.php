<?php

namespace App\Http\Controllers\Admin\Dashboard\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use App\Models\Admin;
use App\Models\Admin\Dashboard\Public\ModelDashboardChecklist5R;
use App\Models\Admin\Module\GetConfigDashboard5R;
use App\Models\Admin\Module\GetOwner;
use App\Models\Admin\Module\PostUserView;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class DashboardChecklist5R extends Controller
{
    public function ViewPageDashboardChecklist5R(Request $request)
    {

        //return view('admin.dashboard.public.dashboardchecklist5r');

        try{
            // Start Section Untuk Insert Ke Log User Ok
        //Mengambil nilai id yang sedang login
            $id=Auth::guard('admin')->id();
            $data= Admin::find($id); 
            $userid = $data->userid;

            // Ambil PageName dari request
            $pagename = $request->query('PageName', '');  
            $pagenamedetail = $pagename;
            $ipaddress = $request->ip();

            // Ambil nilai Facility_ID, Relasi, dan Type dari session atau request
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', ''); 
            $RELASI = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', ''); 
            $Type = $request->input('Type', ''); // Ambil parameter Type dari request atau default kosong 

            // Jika tidak ada nilai yang diperlukan, set default atau tampilkan pesan error
            if (!$facilityID || !$RELASI) {
                return back()->with('error', 'Facility information not found.');
            } 
           
            // Panggil stored procedure untuk insert log
            // try {
            //     PostUserView::PostUserViewPage($facilityID, $pagename, $pagenamedetail, $userid,$RELASI,$ipaddress);
            // } catch (\Exception $e) {
            //     Log::error('Error logging user view: ' . $e->getMessage());
            //     return back()->with('error', 'Failed to log page view.');
            // }
            // Panggil stored procedure untuk insert log hanya jika belum dicatat di session
                $logSessionKey = "logged_view_{$id}_{$pagename}_{$facilityID}_{$RELASI}";
                if (!session()->has($logSessionKey)) {
                    try {
                        PostUserView::PostUserViewPage($facilityID, $pagename, $pagenamedetail, $userid, $RELASI, $ipaddress);
                        // Tandai bahwa log sudah dicatat
                        session([$logSessionKey => true]);
                    } catch (\Exception $e) {
                        Log::error('Error logging user view: ' . $e->getMessage());
                        return back()->with('error', 'Failed to log page view.');
                    }
                }

        // End Section Untuk Insert Ke Log User Ok

        // Ambil data dari stored procedure menggunakan nilai dari session
        //Ini Menampilkan Data Site Detail 
        //Cek apakah data sudah ada di session berdasarkan ID pengguna
        if (session()->has("dataarea5r_{$id}_{$RELASI}") && session()->has("datedept5r_{$id}_{$RELASI}") && session()->has("dataowner5r_{$id}_{$RELASI}")) {
            // Jika semua data sudah ada di session, ambil dari session
            $dataarea5r = session("dataarea5r_{$id}_{$RELASI}");
            $datedept5r = session("datedept5r_{$id}_{$RELASI}");
            $dataowner5r = session("dataowner5r_{$id}_{$RELASI}");
            
        } else {
            // Jika belum ada, query ke database dan simpan di session
            $dataarea5r = GetConfigDashboard5R::getArea5R($facilityID,$RELASI,$Type); 
            $datedept5r =GetConfigDashboard5R::getDept5R($facilityID,$RELASI,$Type);
            $dataowner5r = GetOwner::getOwnerData($facilityID, $RELASI, $Type);

            // Simpan data di session menggunakan ID pengguna
            session(["dataarea5r_{$id}_{$RELASI}" => $dataarea5r]); 
            session(["datedept5r_{$id}_{$RELASI}" => $datedept5r]); 
            session(["dataowner5r_{$id}_{$RELASI}" => $dataowner5r]);
            
        } 
        //dd(session("dataowner5r_{$id}_{$RELASI}"));
        // dd($dataowner5r);

        $tableHeaders1 = []; 
        $tableHeaders2 = [];   
        $tableHeaders3 = [];   
       
        //End Section Menampilkan Site Detail
        // Kirim data ke view
            return view('admin.dashboard.public.dashboardchecklist5r', compact('dataarea5r', 'datedept5r', 'dataowner5r'));


        } catch (\Exception $e) {
            Log::error('Error load page : ' . $e->getMessage());
            return back()->with([
                'message' => 'Failed to load page .',
                'alert-type' => 'warning'
            ]);
        } 
    }

    public function ViewPageArea5R(Request $request)
    {
        try {
            $Type = $request->input('dept5r');
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? null;
            $RELASI = $facilityInfo[0]['Relasi'] ?? null;

            if (!$Type|| !$facilityID || !$RELASI) {
                return response()->json(['error' => 'Missing required parameters'], 400);
            }

            // Panggil stored procedure melalui model
            $dataarea5r = GetConfigDashboard5R::getArea5R($facilityID, $RELASI, $Type);

            return response()->json($dataarea5r);
            
        } catch (\Exception $e) {
            Log::error('Error fetching Area5R: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch Area5R'], 500);
        }
    }

    public function ViewPageSummaryDashboardChecklist5R(Request $request)
    {
        try {

            // Ambil ID admin yang sedang login
            $id = Auth::guard('admin')->id(); 
            //session()->forget("input_databr_{$id}");
            // Ambil nilai Facility_ID, Relasi, dan Type dari session atau request
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', ''); 
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');  

            $StartDate = $request->input('start_date'); 
            $EndDate = $request->input('end_date');  
            $Owner = $request->input('selected_owners', ['ALL']); 
            // Cek apakah $selectedOwners berupa array
            if (is_array($Owner)) {
                // Jika array, gabungkan menjadi string dengan delimiter ";"
                $ownerString = implode(';', $Owner);
            } else {
                // Jika bukan array, langsung gunakan sebagai string
                $ownerString = $Owner;
            }

            // // Debug untuk memastikan hasilnya benar
            // dd($ownerString);


            $Dept = $request->input('dept5r'); 
            $Area = $request->input('area5r'); 
            $Status = $request->input('status5r'); 

             // Validasi input owner
             $validator = Validator::make($request->all(), [
                'selected_owners' => 'required', 
            ]);

            if ($validator->fails()) {
                 //Inget kalo ada eror kembaliin ke halaman view soalnya biar metodenya get ya, biar gak tabrakan sama post
                return redirect()->route('admin.dashboard.public.dashboardchecklist5r')
                ->with([
                    'message' => 'Owner Belum Di Pilih',
                    'alert-type' => 'warning'
                ])->withInput();  // Menjaga data form yang telah diinput
            } 
            
           
            $dataarea5r = session("dataarea5r_{$id}_{$Relasi}");
            $datedept5r = session("datedept5r_{$id}_{$Relasi}");
            $dataowner5r = session("dataowner5r_{$id}_{$Relasi}");

            // Simpan data ke session dengan key yang termasuk ID admin
            session()->put("input_datad5r_{$id}_{$Relasi}", [
                'start_date' => $StartDate,
                'end_date' => $EndDate,
                'dept5r' => $Dept,
                'selected_owners' => is_array($Owner) ? $Owner : [$Owner], 
                'area5r' => $Area,
                'status5r' => $Status,
            ]);

            // dd($Owner);
            // dd($request);
            // dd(session()->all());
            
            
            $dataTables = ModelDashboardChecklist5R::getDataView($facilityID,$Relasi,$ownerString, $StartDate, $EndDate, $Dept, $Area, $Status);
            //dd($dataTables); // Akan menghentikan eksekusi dan menampilkan isi dari $dataTables

            // Jika Anda juga ingin memeriksa dataowner5r
            //dd($dataowner5r); // Tempatkan ini di mana Anda mengambil data owner

            // Kirim data ke view
            return view('admin.dashboard.public.dashboardchecklist5r', [
                'dataTable1' => $dataTables['tabel1'], 
                'dataTable2' => $dataTables['tabel2'],
                'dataTable3' => $dataTables['tabel3'], 
               
                'tableHeaders1' => $dataTables['headers1'], // Header untuk tabel pertama
                'tableHeaders2' => $dataTables['headers2'], // Header untuk tabel kedua
                'tableHeaders3' => $dataTables['headers3'], // Header untuk tabel ketiga

                'dataarea5r' => $dataarea5r, 
                'datedept5r' => $datedept5r, 
                'dataowner5r'=> $dataowner5r,  
            ])
            ->with([
                'message' => 'Data berhasil diambil',
                'alert-type' => 'success'
            ]);


        } catch (\Exception $e) {
          
            // Redirect ke halaman report_throughput dengan pesan error
            return redirect()->route('admin.dashboard.public.dashboardchecklist5r')
                             ->with([
                                'message' => 'Database Sedang Restore, Tunggu Beberapa Saat',
                                'alert-type' => 'warning'
                            ]);
        }
    }

    public function ViewPageDetailDashboardChecklist5R(Request $request)
    {
    
        try {
            $facilityInfo = session('facility_info', []); 
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', ''); 
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');  
            $StartDate = $request->input('startDate');  
            $EndDate = $request->input('endDate');  
            $Owner = $request->input('selectedOwners', ['ALL']); 
            if (is_array($Owner)) { 
                $ownerString = implode(';', $Owner);
            } else { 
                $ownerString = $Owner;
            }
            $Dept = $request->input('dept'); 
            $Area = $request->input('area'); 
            $Status = $request->input('statusclick');  
          
            $validator = Validator::make($request->all(), [
                'selectedOwners' => 'required', 
                'statusclick' => 'required', // Pastikan status tidak kosong
            ]);

            if ($validator->fails()) {
                return redirect()->route('admin.dashboard.public.dashboardchecklist5r')
                    ->with([
                        'message' => 'Owner Belum Di Pilih, atau Parameter Belum Lengkap',
                        'alert-type' => 'warning'
                    ])->withInput();
            }

            $dataTablesDetail = ModelDashboardChecklist5R::getDataDetailPie($facilityID, $Relasi, $ownerString, $StartDate, $EndDate, $Dept, $Area, $Status);
            //dd($dataTablesDetail['tabelpie']);
             //dd($dataTablesDetail['headerspie']);
            //Mengembalikan response JSON untuk digunakan di frontend
            return response()->json([
                'success' => true,
                'data' => [
                    'tabelpie' => $dataTablesDetail['tabelpie'], // Data tabel
                    'headerspie' => $dataTablesDetail['headerspie'], // Header tabel
                ]
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard.public.dashboardchecklist5r')
                ->with([
                    'message' => 'Database Sedang Restore, Tunggu Beberapa Saat',
                    'alert-type' => 'warning'
                ]);
        }
    }





}
          



