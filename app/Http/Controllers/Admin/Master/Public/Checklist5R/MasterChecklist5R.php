<?php

namespace App\Http\Controllers\Admin\Master\Public\Checklist5R;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Module\PostUserView;
use App\Models\Admin\Module\GetValidasiUserSPV;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin\Master\Public\Checklist5R;
use App\Models\Admin\Module\GetDepartement5R;
use Illuminate\Support\Facades\Log; 
use App\Models\Admin\Module\GetRandomData;
use App\Models\Admin\Module\GetSiteS2Support;
use App\Models\Admin\Module\GetOwner;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Validator; 
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Mews\Captcha\Facades\Captcha;
use PhpParser\Node\Stmt\TryCatch;

class MasterChecklist5R extends Controller
{
   
    public function ViewPagePoint5R(Request $request)
    { 

        try {
            
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
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('Relasi', ''); 
            $Type = '2';
            // Jika tidak ada nilai yang diperlukan, set default atau tampilkan pesan error
            if (!$facilityID || !$Relasi) {
                return back()->with('error', 'Facility information not found.');
            }  

            $result = getValidasiUserSPV::getDataSPV($userid);
            
            // dd($result);
            // Cek apakah hasilnya tidak kosong atau null
            if (!empty($result)) {
                
                try {

                    PostUserView::PostUserViewPage($facilityID, $pagename, $pagenamedetail, $userid,$Relasi,$ipaddress);

                } catch (\Exception $e) {
                    Log::error('Error logging user view: ' . $e->getMessage());
                    return back()->with('error', 'Failed to log page view.');
                }
                
                if (session()->has("dataowner5r_{$id}_{$Relasi}") && session()->has("datadept5r_{$id}_{$Relasi}")) {
                    // Jika semua data sudah ada di session, ambil dari session
                    $dataowner5r = session("dataowner5r_{$id}_{$Relasi}");  
                    $datadept5r = session("datadept5r_{$id}_{$Relasi}");  

                } else {
                    // Jika belum ada, query ke database dan simpan di session
                    $dataowner5r = GetOwner::getOwnerData($facilityID, $Relasi, $Type);  
                    $datadept5r = GetDepartement5R::getDept5R($facilityID, $Relasi, $Type);
                    // Simpan data di session menggunakan ID pengguna 
                    session([" dataowner5r_{$id}_{$Relasi}" =>  $dataowner5r]);  
                    session([" datadept5r_{$id}_{$Relasi}" =>  $datadept5r]); 
                    
                } 
             
                $dataTable= Checklist5R::getDataViewPoint5R($facilityID,$Relasi);
                //dd($dataTable);
                // dd($dataTable);
                $notification = array(
                    'message' => 'Data Insert Successfully',
                    'alert-type' => 'success'
                );
    
                return view('admin.master.public.Checklist5R.MasterPoint5R', compact('dataTable', 'dataowner5r','datadept5r'))
                ->with($notification);

            } else {
                // Redirect kembali jika tidak ada hasil
                // return back()->with('error', 'No data found for the given facility ID.');
                return redirect()->route('admin.dashboard') // Ganti 'home' dengan nama route halaman Anda
                ->with([
                    'message' => 'Job Level Minimal Adalah Supervisor (2C)',
                    'alert-type' => 'error',
                ]);
            
            }
            
        } catch (\Exception $e) {
            Log::error('Error logging user view: ' . $e->getMessage());
            return back()->with('error', 'Failed to log page view.');
        } 
       
    }

    public function ViewPageSaveJamPoint5R(Request $request)
    { 
        try {
            
            $id = Auth::guard('admin')->id(); 
            //session()->forget("input_databr_{$id}");
            // Ambil nilai Facility_ID, Relasi, dan Type dari session atau request
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', ''); 
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');  


            $validator = Validator::make($request->all(), [
                'area5r' => 'required|regex:/^[a-zA-Z0-9, \n.]+$/', // Hanya angka dan huruf, maksimal 30 karakter  
                'point1' => 'required', // Hanya angka, huruf, dan koma |regex:/^[a-zA-Z0-9,]+$/
                'point2' => 'required', // Hanya angka, huruf, dan koma
                'point3' => 'required', // Hanya angka, huruf, dan koma
                'point4' => 'required', // Hanya angka, huruf, dan koma
            ]);
    
            if ($validator->fails()) {
                // Ambil pesan kesalahan
                $errors = $validator->errors()->all(); // Mendapatkan semua pesan kesalahan sebagai array
                $errorMessage = implode(', ', $errors); // Menggabungkan pesan kesalahan menjadi string
        
                return back()->with('error', "Failed Insert Data: $errorMessage");
            } 
            
            $Owner = $request->input('owner5r'); 
            $Dept = $request->input('dept5r'); 
            $Area = $request->input('area5r'); 
            $PointCheck = $request->input('point5r'); 
            $Point1 = $request->input('point1'); 
            $Point2 = $request->input('point2'); 
            $Point3 = $request->input('point3'); 
            $Point4 = $request->input('point4');  

            //Panggil stored procedure untuk mendapatkan data tabel 
            Checklist5R::insertDataViewPoint5R($facilityID,$Relasi,$Owner,$Dept, $Area, $PointCheck, $Point1, $Point2, $Point3, $Point4 ); 

            // Redirect ke halaman master setelah berhasil menyimpan data, ini mereset ulang semua
            return back()->with('success', 'Data Insert Successfully')->withInput();


             //return redirect()->route('admin.master.public.checklist5r.masterpoint5r')->with('success', 'Data Insert Successfully'); 
      
               
        } catch (\Exception $e) {
            Log::error('Error logging user view: ' . $e->getMessage());
            return back()->with('error', 'Failed Insert Data, Area  Harus Huruf dan Angka.');
        } 
       
    }

    public function updateShiftPoint5R(Request $request)
    {
        try {
            

            $validator = Validator::make($request->all(), [
                'area' => 'required|regex:/^[a-zA-Z0-9, \n.]+$/', // Hanya angka dan huruf, maksimal 30 karakter 
                'point1' => 'required', // Hanya angka, huruf, dan koma |regex:/^[a-zA-Z0-9,]+$/
                'point2' => 'required', // Hanya angka, huruf, dan koma
                'point3' => 'required', // Hanya angka, huruf, dan koma
                'point4' => 'required', // Hanya angka, huruf, dan koma
            ]);
    

            if ($validator->fails()) {
                // Ambil pesan kesalahan
                $errors = $validator->errors()->all(); // Mendapatkan semua pesan kesalahan sebagai array
                $errorMessage = implode(', ', $errors); // Menggabungkan pesan kesalahan menjadi string
        
                return back()->with('error', "Failed Insert Data: $errorMessage");
            } 
            
            $facilityInfo = session('facility_info', []);
            // Ambil data yang telah divalidasi
            $whseid =$facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', ''); 
            $relasi =$facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');    
            $owner = $request->input('owner');  
            $dept_name = $request->input('dept_name'); 
            $area = $request->input('area'); 
            $point_check = $request->input('point_check'); 
            $point1 = $request->input('point1'); 
            $point2 = $request->input('point2'); 
            $point3 = $request->input('point3');  
            $point4 = $request->input('point4');   

            // Eksekusi stored procedure untuk insert/update
            Checklist5R::updateDataViewPoint5R($whseid,$owner,$relasi, $dept_name,$area , $point_check, $point1, $point2, $point3, $point4 );

            // Menambahkan notifikasi sukses
            return response()->json([
                'success' => true,
                'message' => 'Data Updated Successfully',
            ]);
            
            //admin.master.public.checklist5r.masterpoint5r_update
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function deleteShiftPoint5R(Request $request)
    {
        try {
             
            $facilityInfo = session('facility_info', []);
            // Ambil data yang telah divalidasi
            $whseid =$facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', ''); 
            $owner = $request->input('owner');
            $Relasi =$facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');  
            $deptName = $request->input('dept_name');
            $area = $request->input('area');
            $pointCheck = $request->input('point_check');
        
         
            // Eksekusi stored procedure untuk menghapus data
            Checklist5R::deleteDataViewPoint5R( $whseid,  $owner, $Relasi, $deptName, $area, $pointCheck);

            // Menambahkan notifikasi sukses
            return response()->json([
                'success' => true,
                'message' => 'Data Deleted Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }



// Pembatas
    public function ViewPageMasterJam5R(Request $request)
    {
        try {
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
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('Relasi', ''); 

            // Jika tidak ada nilai yang diperlukan, set default atau tampilkan pesan error
            if (!$facilityID || !$Relasi) {
                return back()->with('error', 'Facility information not found.');
            }  
            
            $Shift = '';
            $Start1 ='';  
            $End1 = '';    
            $Start2 ='';  
            $End2= '';    
            // Shift kedua dari jam 8 malam sampai 5 pagi hari berikutnya
            // $Start2 = date('Y-m-d 20:00:00', strtotime('+12 hours'));  // Hari ini jam 8 malam
            // $End2 = date('Y-m-d 05:00:00', strtotime('+1 day'));     

            $Start11 = date('Y-m-d 08:00:00');  // Hari ini jam 8:00 pagi
            $End11 = date('Y-m-d 08:00:00'); 
            $Start22 = date('Y-m-d 08:00:00');  // Hari ini jam 8:00 pagi
            $End22= date('Y-m-d 08:00:00');    // Hari ini jam 17:00 (contoh untuk akhir shift 1)
            // Ekstrak bagian waktu (jam, menit, detik)
            $startTime1 = substr($Start11, 11); // Mengambil '08:00:00'
            $endTime1 = substr($End11, 11);     // Mengambil '17:00:00'
            $startTime2 = substr($Start22, 11); // Mengambil '20:00:00'
            $endTime2 = substr($End22, 11);     // Mengambil '05:00:00' 

            $result = getValidasiUserSPV::getDataSPV($userid);
            $Type = '2';

            $datadept5rjam = GetDepartement5R::getDept5R($facilityID,$Relasi,$Type);
            
            //dd($result);
            // Cek apakah hasilnya tidak kosong atau null
            if (!empty($result)) {
                 // Panggil stored procedure untuk insert log
                try {
                    PostUserView::PostUserViewPage($facilityID, $pagename, $pagenamedetail, $userid,$Relasi,$ipaddress);
                } catch (\Exception $e) {
                    Log::error('Error logging user view: ' . $e->getMessage());
                    return back()->with('error', 'Failed to log page view.');
                }

                if (session()->has("dataowner5rjam_{$id}_{$Relasi}") && session()->has("datadept5r_{$id}_{$Relasi}")) {
                    // Jika semua data sudah ada di session, ambil dari session
                    $dataowner5rjam = session("dataowner5rjam_{$id}_{$Relasi}");  
                    $datadept5rjam = session("datadept5rjam_{$id}_{$Relasi}");  

                } else {
                    // Jika belum ada, query ke database dan simpan di session
                    $dataowner5rjam = GetOwner::getOwnerData($facilityID, $Relasi, $Type);   
                    $datadept5rjam = GetDepartement5R::getDept5R($facilityID,$Relasi,$Type);
                    // Simpan data di session menggunakan ID pengguna 
                    session([" datadept5rjam_{$id}_{$Relasi}" =>  $datadept5rjam]); 
                    session([" dataowner5rjam_{$id}_{$Relasi}" =>  $dataowner5rjam]);   
                }  

              
                try {
                    $dataTable= Checklist5R::getDataView($facilityID,$Relasi,$Shift, $Start1, $End1, $Start2, $End2);
                    $notification = array(
                        'message' => 'Data Insert Successfully',
                        'alert-type' => 'success'
                    );
                    // dd($dataTable);
                    return view('admin.master.public.Checklist5R.MasterJam5R', compact('dataTable','dataowner5rjam', 'datadept5rjam','startTime1', 'endTime1', 'startTime2', 'endTime2'))
                    ->with($notification);
                    
                } catch (\Exception $e) {
                    Log::error('Error logging load data view: ' . $e->getMessage());
                    return back()->with('error', 'Failed to load data view.');
                } 

            } else {

                // Redirect kembali jika tidak ada hasil
                //return back()->with('error', 'No data found for the given facility ID.');
                return redirect()->route('admin.dashboard') // Ganti 'home' dengan nama route halaman Anda
                    ->with([
                        'message' => 'Job Level Minimal Adalah Supervisor (2C)',
                        'alert-type' => 'error',
                    ]);
                
            }
        } catch (\Exception $e) {
            Log::error('Error logging user view: ' . $e->getMessage());
            return back()->with('error', 'Failed to log page view.');
        }

        
    } 

    public function ViewPageSaveJam5R(Request $request)
    {   
        //dd(Session::all());
        // Ambil nilai Facility_ID, Relasi, dan Type dari session atau request
        $facilityInfo = session('facility_info', []);
        $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', ''); 
        $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('Relasi', ''); 

        // Jika tidak ada nilai yang diperlukan, set default atau tampilkan pesan error
        if (!$facilityID || !$Relasi) {
            return back()->with('error', 'Facility information not found.');
        }   


        $Owner = $request->input('owner5rjam');
        $Dept = $request->input('dept5rjam');
        $Shift = $request->input('shift_by');
        $Start1 = $request->input('start1');
        $End1 = $request->input('end1');
        $Start2 = $request->input('start2');
        $End2 = $request->input('end2');



        try {
            // Insert data
            Checklist5R::insertDataView($facilityID,$Relasi,$Owner,$Dept,$Shift, $Start1, $End1, $Start2, $End2);
            //<?php dd(Session::all()); Ini untuk ngecek semua session //
            // Redirect ke halaman master setelah berhasil menyimpan data
            return redirect()
            ->route('admin.master.public.checklist5r.masterjam5r')
            ->with([
                'message' => 'Data Insert Successfully',
                'alert-type' => 'success'
            ]);
        
        } catch (\Exception $e) {
            // Tangkap error dan kirim pesan ke user  
            return redirect()->back()->with([
                'message' => 'Data Gagal Insert, Silahkan Cek Kembali',
                'alert-type' => 'error'
            ]);
        }
    }

    public function updateShiftPoint(Request $request)
    {
        try {
            // Ambil data dari request
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', ''); 
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('Relasi', ''); 

            $Owner = $request->input('owner');
            $Dept = $request->input('dept5rjam');
            $Adddate = $request->input('add_date');
            $Shift = $request->input('shift_by');
            $Start1 = $request->input('start1');
            $End1 = $request->input('end1');
            $Start2 = $request->input('start2');
            $End2 = $request->input('end2');

            // Eksekusi stored procedure untuk insert/update
            Checklist5R::insertDataView($facilityID,$Relasi,$Owner,$Dept,$Shift, $Start1, $End1, $Start2, $End2);

            // Menambahkan notifikasi sukses
            return response()->json([
                'success' => true,
                'message' => 'Data Updated Successfully',
            ]);
            

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function deleteShiftPoint(Request $request)
    {
        try {
            // Ambil data dari request
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', ''); 
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('Relasi', ''); 

            $Owner = $request->input('owner');
            $Dept = $request->input('dept5rjam');
            $Adddate = $request->input('add_date');
            $Shift = $request->input('shift_by');
            $Start1 = $request->input('start1');
            $End1 = $request->input('end1');
            $Start2 = $request->input('start2');
            $End2 = $request->input('end2');

            // Eksekusi stored procedure untuk insert/update
            Checklist5R::deleteDataView($facilityID,$Relasi,$Owner,$Dept,$Shift, $Start1, $End1, $Start2, $End2);

            // Menambahkan notifikasi sukses
            return response()->json([
                'success' => true,
                'message' => 'Data Deleted Successfully',
            ]);
            

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

}




// public function ViewPageSaveJam5R(Request $request) --- Cara Lawas
    // {
    //      // Ambil nilai Facility_ID, Relasi, dan Type dari session atau request
    //      $facilityInfo = session('facility_info', []);
    //      $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', ''); 
    //      $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('Relasi', ''); 
 
    //       // Jika tidak ada nilai yang diperlukan, set default atau tampilkan pesan error
    //       if (!$facilityID || !$Relasi) {
    //          return back()->with('error', 'Facility information not found.');
    //      }   

    //      $Start11 = date('Y-m-d 08:00:00');  // Hari ini jam 8:00 pagi
    //      $End11 = date('Y-m-d 08:00:00'); 
    //      $Start22 = date('Y-m-d 08:00:00');  // Hari ini jam 8:00 pagi
    //      $End22= date('Y-m-d 08:00:00');    // Hari ini jam 17:00 (contoh untuk akhir shift 1)
    //       // Ekstrak bagian waktu (jam, menit, detik)
    //      $startTime1 = substr($Start11, 11); // Mengambil '08:00:00'
    //      $endTime1 = substr($End11, 11);     // Mengambil '17:00:00'
    //      $startTime2 = substr($Start22, 11); // Mengambil '20:00:00'
    //      $endTime2 = substr($End22, 11);     // Mengambil '05:00:00'

    //      $Shift = $request->input('shift_by');
    //      $Start1 = $request->input('start1');
    //      $End1 = $request->input('end1');
    //      $Start2 = $request->input('start2');
    //      $End2 = $request->input('end2');

    //     Checklist5R::insertDataView($facilityID,$Relasi,$Shift, $Start1, $End1, $Start2, $End2);  
    //     return view('admin.master.public.Checklist5R.MasterJam5R',compact( 'startTime1', 'endTime1', 'startTime2', 'endTime2'))
    //         ->with([
    //             'message' => 'Data berhasil diambil',
    //             'alert-type' => 'success'
    //     ]);

        
    // }


