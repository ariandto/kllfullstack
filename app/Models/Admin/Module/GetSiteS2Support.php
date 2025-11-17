<?php

namespace App\Models\Admin\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GetSiteS2Support extends Model
{
    use HasFactory;
     // Debug parameter yang dikirim
        // dd([
        //     'facilityID' => $facilityID,
        //     'relasi' => $relasi,
        //     'type' => $type
        // ]);
         
        // Tentukan koneksi database yang akan digunakan oleh model ini
        protected $connection = 'sqlsrv'; // Gunakan koneksi sqlsrv51
    
        public static function getSiteS2($facilityID, $relasi, $Site)
        {
            try {
                // Gunakan connection dari properti model
                $query = "SET NOCOUNT ON; EXEC [Rizki_Udsp_Monitoring_ADF] ?, ?, ?, ?, ?, ?, ?, ?, ?";
                // Gunakan DB::connection untuk menggunakan koneksi yang ditentukan di model
                return DB::connection((new static)->connection)->select($query, [
                    'Get Site', $facilityID, $relasi, $Site, '', '', '', '', ''
                ]);
            } catch (\Exception $e) {
                // Tangani exception dengan log atau menampilkan pesan error
                throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
            }
        }

        public static function getSiteDataS2($facilityID, $relasi)
        {
            try {
                // Gunakan connection dari properti model
                $query = "SET NOCOUNT ON; EXEC [Rizki_Udsp_Monitoring_ADF] ?, ?, ?, ?, ?, ?, ?, ?, ?";
                // Gunakan DB::connection untuk menggunakan koneksi yang ditentukan di model
                return DB::connection((new static)->connection)->select($query, [
                    'Get Site Data', $facilityID, $relasi, '', '', '', '', '', ''
                ]);
            } catch (\Exception $e) {
                // Tangani exception dengan log atau menampilkan pesan error
                throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
            }
        }

}
