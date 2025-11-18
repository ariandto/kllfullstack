<?php

namespace App\Models\Admin\Dashboard\Transport;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SCMTransportProfile extends Model
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

        $stmt = $pdo->prepare("EXEC dbo.sp_GetFacilityArmadaPivot ?");
        $stmt->execute([$facilityName]);

        // Result Set 1 → detail facility
        $facilityDetail = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Pindah ke result set ke-2
        $stmt->nextRowset();

        // Result Set 2 → jenis armada pivot
        $armadaPivot = $stmt->fetchAll(\PDO::FETCH_ASSOC);

         // Result Set 3 → jalur index
        $stmt->nextRowset();
        $jalurList = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return [
            'facility_detail' => $facilityDetail,
            'armada_pivot' => $armadaPivot,
            'jalur_index'     => $jalurList
        ];

    } catch (\Exception $e) {
        throw new \Exception('Error executing SP GetFacilityArmadaPivot: ' . $e->getMessage());
    }
}

}
