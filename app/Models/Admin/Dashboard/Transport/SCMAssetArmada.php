<?php

namespace App\Models\Admin\Dashboard\Transport;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SCMAssetArmada extends Model
{
    protected $connection = 'sqlsrv';

    /**
     * Get list of facility
     */
    public static function getFacilityList()
    {
        try {
            $query = "EXEC [dbo].[udsp_Get_Data] 'Get SCM Facility', '', ''";
            $result = DB::select($query);

            return json_decode(json_encode($result), true); // return array
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure Get SCM Facility: ' . $e->getMessage());
        }
    }

    public static function getFacilityArmadaPivot($facilityName)
{
    if (empty($facilityName)) {
        throw new \InvalidArgumentException("Facility name is required.");
    }

    try {
        $pdo = DB::connection('sqlsrv')->getPdo();

        // Pastikan SP name-nya benar: sp_GetFacilityArmadaPivotv2
        $stmt = $pdo->prepare("EXEC dbo.sp_GetFacilityArmadaPivotv2 ?");
        $stmt->execute([$facilityName]);

        // SP ini hanya punya 1 result set (pivot armada)
        $armadaPivot = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return [
            'pivot' => $armadaPivot,
        ];

    } catch (\Exception $e) {
        throw new \Exception('Error executing SP GetFacilityArmadaPivotv2: ' . $e->getMessage());
    }
}

  
}
