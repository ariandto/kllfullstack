<?php

namespace App\Models\Admin\Dashboard\Transport;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelInlinePlan extends Model
{
    protected $connection = 'sqlsrv';

    // Function untuk mengambil fasilitas dari stored procedure
    public static function getFacilities($facility)
    {
        try {
            $query = "EXEC [udsp_Get_Data] ?,?,?,?,?";
            return DB::select($query, ['Get Owner InlinePlan', '', '', $facility, '']);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure for facilities: ' . $e->getMessage());
        }
    }

    public static function getInLinePlanning($startDate, $endDate, $facility, $owners, $RELASI)
    {
        try {
            $query = "SET NOCOUNT ON; EXEC [Monitoring_Progress_LC] ?, ?, ?, ?, ?, ?";
            // dd($owners);
            // $ownersString = implode(',', $owners);
            $ownersString = implode(';', array_map('strtoupper', $owners));
            // dd($query, ['PLANDELIVERY', $startDate, $endDate, 'WMWHSE10RTL', $ownersString, $facility]);
            return DB::select($query, ['PLANDELIVERY', $startDate, $endDate, $RELASI, $ownersString, $facility]);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure for InLinePlanning: ' . $e->getMessage());
        }
    }
    public static function getLcDetail($noLc)
    {
        try {
            // Panggil stored procedure untuk mendapatkan detail LC berdasarkan nomor LC
            $query = "EXEC [dbo].[Monitoring_Progress_LC_DETAIL] ?";
            return DB::select($query, [$noLc]);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
}
