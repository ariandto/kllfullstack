<?php

namespace App\Models\Admin\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetValidasiUserSPV extends Model
{
    use HasFactory;

    // Tentukan koneksi database yang akan digunakan oleh model ini
    protected $connection = 'sqlsrv';

    public static function getDataSPV($userid)
    {
        try {
            // Log the facilityID for debugging
            Log::info('Facility ID: ' . $userid);
           
            // Query with parameters
            $query = "SET NOCOUNT ON; EXEC [udsp_Get_Data] ?, ?, ?, ?, ?";
            $results = DB::select($query, ['Validasi Job Level Code', '', '', $userid, '']);
    
            // Log the results
            Log::info('Stored Procedure Results: ', (array) $results);
    
            return $results;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
    
}
