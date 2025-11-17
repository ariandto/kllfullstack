<?php

namespace App\Models\Admin\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GetRandomData extends Model
{
    use HasFactory;

    // Method ini menggunakan koneksi 'sqlsrv'
    public static function contoh($facilityID, $relasi, $Site)
    {
        try {
            $query = "SET NOCOUNT ON; EXEC [Rizki_Udsp_Monitoring_ADF] ?, ?, ?, ?, ?, ?, ?, ?, ?";
            // Gunakan connection sqlsrv51
            return DB::connection('sqlsrv')->select($query, [
                'Get Site',
                $facilityID,
                $relasi,
                $Site,
                '',
                '',
                '',
                '',
                ''
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    ///getDownloadData
    public static function getDownloadData()
    {
        try {
            $query = "SET NOCOUNT ON; exec [Tarik_OS_Order_RPA]";
            // Gunakan connection sqlsrv51
            return DB::connection('sqlsrv')->select($query, []);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    // Method ini menggunakan koneksi 'sqlsrv51'
    public static function getComboDamageKategori($facilityID, $relasi)
    {
        try {
            $query = "SET NOCOUNT ON; EXEC [Rizki_Udsp_Monitoring_ADF] ?, ?, ?, ?, ?, ?, ?, ?, ?";
            // Gunakan connection sqlsrv
            return DB::connection('sqlsrv')->select($query, [
                'Get Damage Kategori',
                $facilityID,
                $relasi,
                '',
                '',
                '',
                '',
                '',
                ''
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    // Method ini menggunakan koneksi 'sqlsrv51'
    public static function getComboDamageFrom($facilityID, $relasi)
    {
        try {
            $query = "SET NOCOUNT ON; EXEC [Rizki_Udsp_Monitoring_ADF] ?, ?, ?, ?, ?, ?, ?, ?, ?";
            // Gunakan connection sqlsrv51
            return DB::connection('sqlsrv')->select($query, [
                'Get Damage From',
                $facilityID,
                $relasi,
                '',
                '',
                '',
                '',
                '',
                ''
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getComboDamageType($facilityID, $relasi)
    {
        try {
            $query = "SET NOCOUNT ON; EXEC [Rizki_Udsp_Monitoring_ADF] ?, ?, ?, ?, ?, ?, ?, ?, ?";
            // Gunakan connection sqlsrv51
            return DB::connection('sqlsrv')->select($query, [
                'Get Hold Type',
                $facilityID,
                $relasi,
                '',
                '',
                '',
                '',
                '',
                ''
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
}
