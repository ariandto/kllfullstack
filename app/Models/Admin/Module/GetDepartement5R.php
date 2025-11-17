<?php
namespace App\Models\Admin\Module; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GetDepartement5R extends Model
{
    use HasFactory;

        // Tentukan koneksi database yang akan digunakan oleh model ini
        protected $connection = 'sqlsrv';
        public static function getDept5R($facilityID, $Relasi, $Type)
        {
            try {
                // dd($facilityID, $Relasi);
                // Ensure there are no unclosed quotes or misplaced characters
                $query = "SET NOCOUNT ON; EXEC [udsp_Get_Data] ?, ?, ?, ?, ?";
                $result = DB::select($query, ['Get Departement Name 5R', $facilityID, $Relasi, '', '']);
                
                return $result;
            } catch (\Exception $e) {
                // Log the error or handle it as needed
                throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
            }
            
        }

}
