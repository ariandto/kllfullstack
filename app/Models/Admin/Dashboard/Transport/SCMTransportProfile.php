<?php

namespace App\Models\Admin\Dashboard\Transport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class SCMTransportProfile extends Model
{
    // Model ini pakai stored procedure, jadi tidak terhubung langsung ke tabel
    protected $connection = 'sqlsrv';

    public static function getFacilityList()
    {
        try {
            $query = "EXEC [dbo].[udsp_Get_Data] 'Get SCM Facility', '', ''";
            return DB::select($query);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure Get SCM Facility: ' . $e->getMessage());
        }
    }

    public static function getFacilityArmadaPivot($facilityName)
{
    try {
        $query = "EXEC dbo.sp_GetFacilityArmadaPivot ?";
        return DB::select($query, [$facilityName]);
    } catch (\Exception $e) {
        throw new \Exception('Error executing SP GetFacilityArmadaPivot: ' . $e->getMessage());
    }
}


    
}