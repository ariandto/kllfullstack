<?php

namespace App\Http\Controllers\Admin\Report\Outbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use App\Models\Admin\Module\GetOwner;
use App\Models\Admin\Module\GetTypeOrder;
use App\Models\Admin\Module\PostUserView;
use App\Models\Admin;
use App\Models\Admin\Report\Outbound\DemandInOutBacklog; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ReportDemandInOutbacklog extends Controller
{
    public function ViewPageDemandInOutBacklog(Request $request)
    {
        try {
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

            //Ini Menampilkan Data Owner + Type Order
                // Cek apakah data sudah ada di session berdasarkan ID pengguna
                if (session()->has("dataowner_$id") && session()->has("datatypeorder_$id")) {
                    // Jika data sudah ada di session, ambil dari session
                    $dataowner = session("dataowner_$id");
                    $datatypeorder = session("datatypeorder_$id");
                } else {
                    // Jika belum ada, query ke database dan simpan di session
                    $dataowner = GetOwner::getOwnerData($facilityID, $RELASI, $Type);
                    $datatypeorder = GetTypeOrder::GetTypeOrder($facilityID, $RELASI);

                    // Simpan data di session menggunakan ID pengguna
                    session(["dataowner_$id" => $dataowner]);
                    session(["datatypeorder_$id" => $datatypeorder]);
                }

            //End Section Menampilkan Data Owner + Type Order
            //dd($dataowner);
            //dd($dataowner);
            // Kirim data ke view
                return view('admin.report.outbound.report_demand_in_out_backlog', compact('dataowner', 'datatypeorder'));
            
            // End Section Kirim data ke view
        } catch (\Exception $e) {
            Log::error('Error load page : ' . $e->getMessage());
            return back()->with([
                'message' => 'Failed to load page .',
                'alert-type' => 'warning'
            ]);
        }
            
    }

    public function ViewSummaryDemandInOutBacklog(Request $request)
    {
        try {
            // Ambil nilai Facility_ID, Relasi, dan Type dari session atau request
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', ''); 
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');  
            $StartDate = $request->input('start_date');
            $EndDate = $request->input('end_date');
            $ViewBy = $request->input('view_by');
            $ViewType =$request->input('filter_by'); 
            $Owner = $request->input('selected_owners', ['ALL']); 
            if (is_array($Owner)) {
                // Jika array, gabungkan menjadi string dengan delimiter ";"
                $ownerString = implode(';', $Owner);
            } else {
                // Jika bukan array, langsung gunakan sebagai string
                $ownerString = $Owner;
            }

            $TypeOrder = $request->input('selected_typeorders', ['ALL']); 
            if (is_array($TypeOrder)) {
                // Jika array, gabungkan menjadi string dengan delimiter ";"
                $TypeOrderString = implode(';', $TypeOrder);
            } else {
                // Jika bukan array, langsung gunakan sebagai string
                $TypeOrderString = $TypeOrder;
            }
            
            // Validasi input owner
            $validator = Validator::make($request->all(), [
                'selected_owners' => 'required', 
                'selected_typeorders' => 'required', 
            ]);

            if ($validator->fails()) {
                // Mengarahkan kembali dengan pesan error
                return redirect()->back()->with([
                    'message' => 'Owner atau Type Order Belum Di Pilih',
                    'alert-type' => 'warning'
                ]);;
            } 
            
           // Ambil ID admin yang sedang login
            $id = Auth::guard('admin')->id(); 

            // Simpan data ke session dengan key yang termasuk ID admin
            session()->put("input_data_demand_$id", [
                'start_date' => $StartDate,
                'end_date' => $EndDate,
                'selected_owners' => is_array($Owner) ? $Owner : [$Owner],
                'selected_typeorders'=> is_array($TypeOrder) ? $TypeOrder : [$TypeOrder],
                'view_by' => $ViewBy,
                'filter_by' => $ViewType
            ]);

            //dd(session()->all());

            // Panggil stored procedure untuk mendapatkan data tabel
            $dataTable = DemandInOutBacklog::getSummaryData($facilityID,  $StartDate, $EndDate, $ownerString,$TypeOrderString,  $ViewType, $ViewBy, $Relasi);
            
            // dd($dataTable);

            // Kirim data ke view
            return view('admin.report.outbound.report_demand_in_out_backlog', compact('dataTable'))
            ->with([
                'message' => 'Data berhasil diambil',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {
          
            // Redirect ke halaman demandinoutbacklog dengan pesan error
            return redirect()->route('admin.report.outbound.demandinoutbacklog')
                             ->with([
                                'message' => 'Database Sedang Restore, Tunggu Beberapa Saat',
                                'alert-type' => 'warning'
                            ]);
        }
    }
        

}

   // dd([
        //     'dataowner' => session()->get("dataowner_$id"),
        //     'datatypeorder' => session()->get("datatypeorder_$id"), 
        // ]);