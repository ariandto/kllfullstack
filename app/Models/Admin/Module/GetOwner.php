<?php

namespace App\Models\Admin\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GetOwner extends Model
{
    use HasFactory;
     // Debug parameter yang dikirim
        // dd([
        //     'facilityID' => $facilityID,
        //     'relasi' => $relasi,
        //     'type' => $type
        // ]);
         
        // Tentukan koneksi database yang akan digunakan oleh model ini
        protected $connection = 'sqlsrv';
        public static function getOwnerData($facilityID, $relasi, $type)
        {
            try {
                // Ensure there are no unclosed quotes or misplaced characters
                $query = "SET NOCOUNT ON; EXEC [SPGetOwner] ?, ?, ?";
                return DB::select($query, [$facilityID, $relasi, $type]);
            } catch (\Exception $e) {
                // Log the error or handle it as needed
                throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
            }
        }

        // SET NOCOUNT ON; ini penting banget ya geys ya

}
