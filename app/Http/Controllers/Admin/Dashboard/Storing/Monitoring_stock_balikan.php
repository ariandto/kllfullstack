<?php

namespace App\Http\Controllers\Admin\Dashboard\Storing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use App\Models\Admin;
use App\Models\Admin\Dashboard\Storing\ModelMonitoringStockBalikan;
use App\Models\Admin\Module\GetRandomData;
use App\Models\Admin\Module\GetSiteS2Support;
use App\Models\Admin\Module\PostUserView;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Module\GetOwner;



class Monitoring_stock_balikan extends Controller
{
    public function ViewPagemonitoringrstockbalikan(Request $request)
    {
        try
        {
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
            try {
                PostUserView::PostUserViewPage($facilityID, $pagename, $pagenamedetail, $userid,$RELASI,$ipaddress);
            } catch (\Exception $e) {
                Log::error('Error logging user view: ' . $e->getMessage());
                return back()->with('error', 'Failed to log page view.');
            }

        // End Section Untuk Insert Ke Log User Ok

        // Ambil data dari stored procedure menggunakan nilai dari session
        //Ini Menampilkan Data Owner 
            // Cek apakah data sudah ada di session berdasarkan ID pengguna
            if (session()->has("dataowner_$id") ) {
                // Jika data sudah ada di session, ambil dari session
                $dataowner = session("dataowner_$id");
                
            } else {
                // Jika belum ada, query ke database dan simpan di session
                $dataowner = GetOwner::getOwnerData($facilityID, $RELASI, $Type); 
                // Simpan data di session menggunakan ID pengguna
                session(["dataowner_$id" => $dataowner]); 
            }
        }
         catch (\Exception $e)
        {
            Log::error('Error load page : ' . $e->getMessage());
            return back()->with([
                'message' => 'Failed to load page .',
                'alert-type' => 'warning'
            ]);
        }

        // Ambil data dari stored procedure menggunakan nilai dari session
        //Ini Menampilkan Data Owner 
            // Cek apakah data sudah ada di session berdasarkan ID pengguna
            if (session()->has("dataowner_$id") ) {
                // Jika data sudah ada di session, ambil dari session
                $dataowner = session("dataowner_$id");
                
            } else {
                // Jika belum ada, query ke database dan simpan di session
                $dataowner = GetOwner::getOwnerData($facilityID, $RELASI, $Type); 
                // Simpan data di session menggunakan ID pengguna
                session(["dataowner_$id" => $dataowner]); 
            }

        // mengabil data dari file blade
        return view('admin.dashboard.storing.Monitoring_Stock_balikan', compact('dataowner'));
    }

    public function ViewPageSummarystockbalikan(Request $request)
    {
        $facilityInfo = session('facility_info', []);
        // Mengambil data dari form Parameter dari Blade
        $type =('getdatastock');
        $sku = $request->input('nomor_sku') ?? '';
        $whseid = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', ''); 
        $relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
        $loc = $request->input('loc') ?? ''; 
        $storerkey = $request->input('selected_owners', ['ALL']);

        if (is_array($storerkey)) {
            // Jika array, gabungkan menjadi string dengan delimiter ";"
            $ownerString = implode(';', $storerkey);
        } else {
            // Jika bukan array, langsung gunakan sebagai string
            $ownerString = $storerkey;
        }
        
        $lpn = $request->input('idlpn')?? '';
        $param1 = '';
        $param2 = '';
        $param3 = '';

        $validator = Validator::make($request->all(), [ 
            'selected_owners' => 'required', 
        ]);

        if ($validator->fails()) {
            // Mengarahkan kembali dengan pesan error
            return redirect()->back()->with([
                'message' => 'Owner Belum di Pilih',
                'alert-type' => 'warning'
            ]);;
        }  
        
        // mengabil data dari model
        $datatabel = ModelMonitoringStockBalikan::getstockeData($type,$sku,$whseid,$relasi,$loc,$ownerString,$lpn,$param1,$param2,$param3);

        return view('admin.dashboard.storing.Monitoring_Stock_balikan', [
            'datatabel1' => $datatabel['tabel1'],
            'tabelheaders1' => $datatabel['headers1']
        ]);


        
    }
        
    // }

    // public function ViewPageSummarystockbalikan(Request $request)
    // {

    //     $facilityInfo = session('facility_info', []);
    //     $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
    //     $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
    //     $StartDate = $request->input('start_date');
    //     $EndDate = $request->input('end_date');
    //     $notto = $request->input('nomor_tto');
    //     $sku = $request->input('nomor_sku');
    //     $loc = $request->input('Loc');
    //     $lpn = $request->input('noLPN');

    //     $datatabel = ModelMonitoringReceieveBalikan::getStockData($facilityID, $StartDate, $EndDate, $Relasi, $notto, $sku, $loc, $lpn);


    //     return view('admin.dashboard.inbound.Report_Receieve_balikan', [
    //         'datatabel2' => $datatabel['tabel2'],
    //         'tabelheaders2' => $datatabel['headers2']
    //     ]);
    // }

    

}