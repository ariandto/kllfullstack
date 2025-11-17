<?php

namespace App\Http\Controllers\Android;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadAPKAndroid extends Controller
{

    public function showDownloadPage()
    {
        return view('admin.android.HalamanDownloadAPK');
    }

    public function downloadFile()
    {
        set_time_limit(0);
        // Path file di FTP
        $filePath = 'APK/scmmobileapp.apk'; // Ganti dengan path file yang sesuai

        // Cek apakah file ada di FTP
        if (Storage::disk('ftp')->exists($filePath)) {
            // Dapatkan isi file dari FTP
            $fileContents = Storage::disk('ftp')->get($filePath);
            
            // Mendapatkan nama file
            $fileName = basename($filePath);
            
            // Mengembalikan response dengan header yang sesuai untuk mengunduh file
            return response()->stream(function () use ($fileContents) {
                echo $fileContents;
            }, 200, [
                'Content-Type' => 'application/vnd.android.package-archive', // Tipe MIME untuk file APK
                'Content-Disposition' => 'attachment; filename="' . basename($filePath) . '"',
            ]);
        }

        // Jika file tidak ditemukan, kembali ke halaman sebelumnya dengan pesan error
        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }
}
