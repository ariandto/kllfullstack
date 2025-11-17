<?php

namespace App\Http\Controllers;

use App\Models\Admin\Module\GetRandomData;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class DownloadApkController extends Controller
{
    // Fungsi untuk menampilkan halaman download
    public function showDownloadPage()
    {
        return view('admin.downloadapk'); // Mengarahkan ke Blade view
    }

    // Fungsi untuk menangani download file APK
    public function downloadApk()
    {
        $filePath = 'public/scmmobileapp.apk'; // Path di storage/app/public

        if (!Storage::exists($filePath)) {
            return redirect()->route('show.download.page')->with('error', 'File tidak ditemukan.');
        }

        return Storage::download($filePath, 'scmmobileapp.apk', [
            'Content-Type' => 'application/vnd.android.package-archive',
        ]);
    }

    public function showDownloadPageOSAHI()
    {
        // Ambil data dari model
        return view('admin.downloadfile.downloadosahi'); // Mengarahkan ke Blade view
    }

    public function downloadOSAHI(Request $request)
    {
        try {
            // Ambil data yang sudah ditampilkan dan dikirim ke halaman
            $dataod = GetRandomData::getDownloadData(); // Ganti dengan nama model & method kamu
            // Simpan data ke session untuk digunakan saat download
            //dd($dataod);

            $data = $dataod; // Asumsi data sudah disimpan di session

            // Jika data kosong
            if (empty($data)) {
                return redirect()->route('show.download.page.osod.ahi')->with('error', 'Tidak ada data untuk diunduh');
            }

            // Ubah ke array untuk penanganan CSV
            $csvData = [];
            $header = array_keys((array) $data[0]);
            $csvData[] = $header;

            foreach ($data as $row) {
                $csvData[] = (array) $row;
            }

            // Buat konten CSV
            $callback = function () use ($csvData) {
                $file = fopen('php://output', 'w');
                foreach ($csvData as $line) {
                    fputcsv($file, $line);
                }
                fclose($file);
            };

            // Nama file CSV
            $filename = 'download_os_od_ahi' . '.csv';

            // Mengirimkan file CSV sebagai stream response
            return Response::stream($callback, 200, [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ]);
        } catch (\Exception $e) {
            // Menangani error dengan pesan yang lebih informatif
            return redirect()->route('show.download.page.osod.ahi')->with('error', 'Terjadi kesalahan saat mengunduh data: ' . $e->getMessage());
        }
    }
}
