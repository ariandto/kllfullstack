<?php

namespace App\Models\Admin\Master\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Master_damage_from extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv';
    
    //Pemisah Ya  Disini 
    public static function insertDataViewLPNBarangRusak($facilityID,$Relasi,$Site, $Owner,$CodeLPN, $LPNDesc )
    {
        try {
            //dd($facilityID,$Relasi,$Shift, $Start1, $End1, $Start2, $End2);
            $query = "SET NOCOUNT ON; EXEC [Rizki_Udsp_Monitoring_ADF_DamageFrom_LPN] ?, ?, ?, ?, ?, ?, ?";
            return DB::statement($query, ['Insert',$facilityID,$Relasi,$Site, $Owner,$CodeLPN, $LPNDesc]);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        } 
    }
    
    public static function updateDataViewLPNBarangRusak($facilityID,$Relasi,$Site, $Owner,$CodeLPN, $LPNDesc )
    {
        try {
            //dd($facilityID,$Relasi,$Shift, $Start1, $End1, $Start2, $End2);
            $query = "SET NOCOUNT ON; EXEC [Rizki_Udsp_Monitoring_ADF_DamageFrom_LPN] ?, ?, ?, ?, ?, ?, ?";
            return DB::statement($query, ['Insert',$facilityID,$Relasi,$Site, $Owner,$CodeLPN, $LPNDesc]);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        } 
    }
    
    
    public static function deleteDataViewLPNBarangRusak($facilityID,$Relasi,$Site, $Owner,$CodeLPN, $LPNDesc)
    {
        try {
            //dd($whseid,  $owner, $Relasi, $deptName, $area, $pointCheck );
            $query = "SET NOCOUNT ON; EXEC [Rizki_Udsp_Monitoring_ADF_DamageFrom_LPN]  ?, ?, ?, ?, ?, ?, ?";
            return DB::statement($query, ['Delete',$facilityID,$Relasi,$Site, $Owner,$CodeLPN, $LPNDesc]);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
    
    public static function getDataViewLPNBarangRusak($facilityID,$Relasi)
    {
        try {
            //dd($facilityID,$Relasi,$Shift, $Start1, $End1, $Start2, $End2);
            $query = "SET NOCOUNT ON; EXEC [Rizki_Udsp_Monitoring_ADF_DamageFrom_LPN]  ?, ?, ?, ?, ?, ?, ?";
            return DB::select($query, ['Data View',$facilityID,$Relasi,'','','','']);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

}



