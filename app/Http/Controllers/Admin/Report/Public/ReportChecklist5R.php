<?php

namespace App\Http\Controllers\Admin\Report\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\Admin\Report\Public\ModelReportChecklist5R;
use App\Models\Admin\Module\GetOwner;
use App\Models\Admin\Module\PostUserView;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Admin\Module\GetConfigDashboard5R;


class ReportChecklist5R extends Controller
{
    public function ViewPageReportChecklist5R(Request $request)
    {

        //return view('admin.dashboard.public.dashboardchecklist5r'); 
        try {
            // Start Section Untuk Insert Ke Log User Ok
            //Mengambil nilai id yang sedang login
            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
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
                PostUserView::PostUserViewPage($facilityID, $pagename, $pagenamedetail, $userid, $RELASI, $ipaddress);
            } catch (\Exception $e) {
                Log::error('Error logging user view: ' . $e->getMessage());
                return back()->with('error', 'Failed to log page view.');
            }
            // End Section Untuk Insert Ke Log User Ok

            // Ambil data dari stored procedure menggunakan nilai dari session
            //Ini Menampilkan Data Site Detail 
            //Cek apakah data sudah ada di session berdasarkan ID pengguna
            if (session()->has("dataowner5rr_{$id}_{$RELASI}")) {
                // Jika semua data sudah ada di session, ambil dari session 
                $dataowner5rr = session("dataowner5rr_{$id}_{$RELASI}");
            } else {
                // Jika belum ada, query ke database dan simpan di session 
                $dataowner5rr = GetOwner::getOwnerData($facilityID, $RELASI, $Type);

                // Simpan data di session menggunakan ID pengguna 
                session(["dataowner5rr_{$id}_{$RELASI}" => $dataowner5rr]);
            }
            //dd(session("dataowner5rr_{$id}_{$RELASI}"));
            // dd($dataowner5rr);

            $tableHeaders1 = [];
            $tableHeaders2 = [];

            //End Section Menampilkan Site Detail
            // Kirim data ke view
            return view('admin.report.public.reportchecklist5r', compact('dataowner5rr'));
        } catch (\Exception $e) {
            Log::error('Error load page : ' . $e->getMessage());
            return back()->with([
                'message' => 'Failed to load page .',
                'alert-type' => 'warning'
            ]);
        }
    }

    public function ViewPageSummaryReportChecklist5R(Request $request)
    {
        // dd($request->method());

        Log::info('Request Method: ' . $request->method()); // Log method request
        Log::info('Request Data: ', $request->all()); // Log data form

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
            //dd($ownerString);

            // Validasi input owner
            $validator = Validator::make($request->all(), [
                'selected_owners' => 'required',
            ]);

            if ($validator->fails()) {
                //Inget kalo ada eror kembaliin ke halaman view soalnya biar metodenya get ya, biar gak tabrakan sama post
                return redirect()->route('admin.report.public.reportchecklist5r')
                    ->with('message', 'Owner Belum Di Pilih')
                    ->with('alert-type', 'warning')
                    ->withInput();
            }

            $dataowner5rr = session("dataowner5rr_{$id}_{$Relasi}");

            // Simpan data ke session dengan key yang termasuk ID admin
            session()->put("input_datar5r_{$id}_{$Relasi}", [
                'start_date' => $StartDate,
                'end_date' => $EndDate,
                'selected_owners' => is_array($Owner) ? $Owner : [$Owner],
            ]);

            // dd($Owner);
            // dd($request);
            // dd(session()->all());


            $dataTables = ModelReportChecklist5R::getDataView($facilityID, $Relasi, $ownerString, $StartDate, $EndDate);
            //dd($dataTables); // Akan menghentikan eksekusi dan menampilkan isi dari $dataTables

            // Jika Anda juga ingin memeriksa dataowner5rr
            //dd($dataowner5rr); // Tempatkan ini di mana Anda mengambil data owner

            // Kirim data ke view
            return view('admin.report.public.reportchecklist5r', [
                'dataTable1' => $dataTables['tabel1'],
                'dataTable2' => $dataTables['tabel2'],
                'dataTable3' => $dataTables['tabel3'],

                'tableHeaders1' => $dataTables['headers1'], // Header untuk tabel pertama
                'tableHeaders2' => $dataTables['headers2'], // Header untuk tabel kedua 
                'tableHeaders3' => $dataTables['headers3'], // Header untuk tabel kedua 

                'dataowner5rr' => $dataowner5rr,
            ])
                ->with([
                    'message' => 'Data berhasil diambil',
                    'alert-type' => 'success'
                ]);
        } catch (\Exception $e) {

            // Redirect ke halaman report_throughput dengan pesan error
            return redirect()->route('admin.report.public.reportchecklist5r')
                ->with([
                    'message' => 'Database Sedang Restore, Tunggu Beberapa Saat',
                    'alert-type' => 'warning'
                ]);
        }
    }


    public function ViewPageExport5RPDF(Request $request)
    {
        $id = Auth::guard('admin')->id();
        //session()->forget("input_databr_{$id}");
        // Ambil nilai Facility_ID, Relasi, dan Type dari session atau request
        $facilityInfo = session('facility_info', []);
        $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
        $RELASI = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
        $Type = '1';
        if (session()->has("datedept5r_{$id}_{$RELASI}")) {
            // Jika semua data sudah ada di session, ambil dari session

            $datedept5r = session("datedept5r_{$id}_{$RELASI}");
        } else {
            // Jika belum ada, query ke database dan simpan di session     

            $datedept5r = GetConfigDashboard5R::getDept5R($facilityID, $RELASI, $Type);
            // Simpan data di session menggunakan ID pengguna

            session(["datedept5r_{$id}_{$RELASI}" => $datedept5r]);
        }

        // Return ke view dengan data
        return view('admin.export.checklist5r_pdf', $request, compact('datedept5r'));
    }

    private function getFtpImageBase64($filename)
    {
        if (!$filename) return null;

        // Tentukan folder berdasarkan nama file
        $folder = '';
        if (str_starts_with($filename, 'Rapi')) {
            $folder = 'Attachment/Checklist5R/Rapi/';
        } elseif (str_starts_with($filename, 'Resik')) {
            $folder = 'Attachment/Checklist5R/Resik/';
        } elseif (str_starts_with($filename, 'Ringkas')) {
            $folder = 'Attachment/Checklist5R/Ringkas/';
        } elseif (str_starts_with($filename, 'Rawat')) {
            $folder = 'Attachment/Checklist5R/Rawat/';
        }

        // Gabungkan folder dengan nama file
        $path = $folder . $filename;

        // Cek apakah file ada di FTP
        if (Storage::disk('ftp')->exists($path)) {
            $fileContents = Storage::disk('ftp')->get($path); // Ambil konten file

            // Buat gambar dari string
            $image = imagecreatefromstring($fileContents);
            if ($image === false) {
                return null; // Jika gagal membuat gambar
            }

            // Kompres gambar
            ob_start();
            imagejpeg($image, null, 50); // 25 adalah kualitas gambar (0-100), semakin rendah semakin terkompresi
            $compressedImage = ob_get_clean();

            // Hapus gambar dari memori
            imagedestroy($image);

            // Konversi ke base64
            $base64 = base64_encode($compressedImage);
            return 'data:image/jpeg;base64,' . $base64; // Kembalikan string base64
        }

        return null; // Jika file tidak ditemukan
    }

    public function GetReportData(Request $request)
    {
        $facilityInfo = session('facility_info', []);
        $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
        $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
        $StartDate = $request->input('start_date');
        $Dept = $request->input('dept5r');
        $Owner = $request->input('selected_owners', ['ALL']);

        $ownerString = is_array($Owner) ? implode(';', $Owner) : $Owner;

        $validator = Validator::make($request->all(), [
            'selected_owners' => 'required',
        ], [
            'selected_owners.required' => 'Silakan pilih setidaknya satu Owner.'
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.export.checklist5rpdf')
                ->with('message', 'Owner Belum Di Pilih')
                ->with('alert-type', 'warning')
                ->withInput();
        }

        $data = ModelReportChecklist5R::getDataViewExportPDF($facilityID, $Relasi, $ownerString, $Dept, $StartDate);

        // Memfilter data untuk menghapus bagian tabel1 dan headers1 jika ada
        if (isset($data['tabel1'])) {
            $data = $data['tabel1'];  // Mengambil hanya data yang ada dalam 'tabel1'
        }

        // Mengembalikan data yang telah difilter
        return response()->json($data); // Ini akan mengembalikan data dalam format JSON 

    }

    public function PrintPageExport5RPDF(Request $request)
    {
        // Panggil GetReportData() untuk mendapatkan data yang diperlukan
        $data = $this->GetReportData($request);

        // Mengakses data asli yang diperlukan
        $data1 = $data->original; // Atau $data['original'] jika data adalah array 

        //dd($data1);
        // Ambil start date dari request
        $StartDate = $request->input('start_date');

        // Proses data untuk menyisipkan gambar dalam format base64
        foreach ($data1 as &$row) { // Gunakan & untuk merujuk langsung ke array
            // Pastikan mengakses dengan indeks array, bukan dengan properti objek
            $row['Doc1'] = $this->getFtpImageBase64($row['Doc1']);
            $row['Doc2'] = $this->getFtpImageBase64($row['Doc2']);
            $row['Doc3'] = $this->getFtpImageBase64($row['Doc3']);
        }

        // Format tanggal untuk nama file
        $formattedDate = date('Y-m-d', strtotime($StartDate));

        // Gunakan nama file yang sesuai dengan format yang diinginkan
        $fileName = "Checklist5R_{$formattedDate}.pdf";

        // Load view untuk menghasilkan PDF dengan data yang sudah diproses
        $pdf = PDF::loadView('admin.export.pdf.checklist5rpdf', ['data1' => $data1]);
        $pdf->setPaper('a4', 'landscape');

        // Return PDF untuk diunduh dengan nama file yang dinamis
        return $pdf->download($fileName);
    }
}

    // private function getFtpImageBase64($filename)
    // {
    //     if (!$filename) return null;

    //     // Tentukan folder berdasarkan nama file
    //     $folder = '';
    //     if (str_starts_with($filename, 'Rapi')) {
    //         $folder = 'Attachment/Checklist5R/Rapi/';
    //     } elseif (str_starts_with($filename, 'Resik')) {
    //         $folder = 'Attachment/Checklist5R/Resik/';
    //     } elseif (str_starts_with($filename, 'Ringkas')) {
    //         $folder = 'Attachment/Checklist5R/Ringkas/';
    //     } elseif (str_starts_with($filename, 'Rawat')) {
    //         $folder = 'Attachment/Checklist5R/Rawat/';
    //     }

    //     // Gabungkan folder dengan nama file
    //     $path = $folder . $filename;

    //     // Cek apakah file ada di FTP
    //     if (Storage::disk('ftp')->exists($path)) {
    //         $fileContents = Storage::disk('ftp')->get($path); // Ambil konten file
    //         $base64 = base64_encode($fileContents); // Konversi ke base64
    //         return 'data:image/jpeg;base64,' . $base64; // Return string base64
    //     }

    //     return null; // Jika file tidak ditemukan 
    // }