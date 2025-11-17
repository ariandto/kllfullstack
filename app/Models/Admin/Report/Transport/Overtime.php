<?php

namespace App\Models\Admin\Report\Transport;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Overtime extends Model
{
    protected $table = null;
    public $timestamps = false;

    public static function getData($nik, $startDate, $endDate)
    {
        try {
            $results = DB::select(
                'EXEC GetLemburDataByNIK @nik = ?, @start_date_lembur = ?, @end_date_lembur = ?',
                [$nik, $startDate, $endDate]
            );


            return collect($results)->map(function ($item) {
                return (object) [
                    'NIK' => $item->NIK ?? null,
                    'Nama_Karyawan' => $item->Nama_Karyawan ?? null,
                    'Jam_Lembur_Roster_Out' => $item->Jam_Lembur_Roster_Out ?? null,
                    'Jam_Selesai_Lembur' => $item->Jam_Selesai_Lembur ?? null,
                    // 'Total_Jam__Lembur' => $item->Total_Jam__Lembur ?? null,
                    'Keterangan_Lembur' => $item->Keterangan_Lembur ?? null,
                    'IsApproved' => (bool)($item->IsApproved ?? false),
                    'Status_Approval' => $item->Status_Approval ?? null,
                    'Approved_By_NIK' => $item->Approved_By_NIK ?? null,
                    'Approved_By_Name' => $item->Approved_By_Name ?? null,
                    'Approve_Time' => !empty($item->Approve_Time)
                        ? Carbon::parse($item->Approve_Time)->format('d-m-Y H:i')
                        : null,
                    'Facility' => $item->Facility ?? null,
                    'Tanggal' => !empty($item->Tanggal)
                        ? Carbon::parse($item->Tanggal)->format('d-m-Y')
                        : null,
                    'Tolerance_Menit' => $item->Tolerance_Menit ?? null,
                    'Jam_Checkin_DC_Aktual' => $item->Jam_Checkin_DC_Aktual ?? null,
                    'Jobdesc_Lembur' => $item->Jobdesc_Lembur ?? null,
                    'Approval_By_System' => $item->Approval_By_System ?? null,
                ];
            });

        } catch (\Exception $e) {
            Log::error('Gagal eksekusi GetLemburDataByNIK', [
                'nik' => $nik,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'error' => $e->getMessage(),
            ]);

            return collect();
        }
    }
}
