<?php

namespace App\Http\Controllers\Admin\Module;

use Illuminate\Http\Request;
use App\Models\Admin\Module\PrinterConfig;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PrinterController extends Controller
{
    public function getPrinter()
    {
        // Dapatkan IP User
        $userIP = request()->ip();

        // Cari Printer berdasarkan IP User
        //$printer = PrinterConfig::where('IPAddress', $userIP)->first();
        // Cek database
        $printer = DB::table('TBPrinterConfig')->where('IPAddress', '10.19.225.94')->first();

        //dd($printer);
        // Jika ditemukan, kembalikan path printer
        if ($printer) {
            return response()->json([
                'success' => true,
                'printerPath' => $printer->PrinterLocation
            ]);
        }

        // Jika tidak ditemukan
        return response()->json([
            'success' => false,
            'message' => 'Printer tidak ditemukan'
        ]);
    }
}
