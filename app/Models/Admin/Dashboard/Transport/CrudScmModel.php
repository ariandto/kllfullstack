<?php

namespace App\Models\Admin\Dashboard\Transport;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CrudScmModel extends Model
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

 public static function execSP($action, $params = [])
    {
        try {

            // SP harus sesuai
            $sql = "
                EXEC dbo.udsp_SCM_Facility_Crud
                    @Action = :Action,
                    @facility_ID = :facility_ID,
                    @Name = :Name,
                    @Is_Loading_Dock = :Is_Loading_Dock,
                    @Opening_Date = :Opening_Date,
                    @Area_Staging_P = :Area_Staging_P,
                    @Area_Staging_L = :Area_Staging_L,
                    @Area_Staging_T = :Area_Staging_T,
                    @Area_Loading_P = :Area_Loading_P,
                    @Area_Loading_L = :Area_Loading_L,
                    @Alamat = :Alamat,
                    @Demand_DO = :Demand_DO,
                    @Capacity_DO = :Capacity_DO,
                    @Capacity_CBM = :Capacity_CBM,
                    @NIK_Leader = :NIK_Leader,
                    @Telp = :Telp,
                    @Background_Image = :Background_Image,
                    @Staff_Up = :Staff_Up,
                    @Driver = :Driver,
                    @Asst_Driver = :Asst_Driver,
                    @WHM = :WHM,
                    @Security = :Security
            ";

            $pdo = DB::connection('sqlsrv')->getPdo();

            $stmt = $pdo->prepare($sql);

            $bindParams = [
                'Action'            => $action,
                'facility_ID'       => $params['facility_ID'] ?? null,
                'Name'              => $params['Name'] ?? null,
                'Is_Loading_Dock'   => $params['Is_Loading_Dock'] ?? null,
                'Opening_Date'      => $params['Opening_Date'] ?? null,
                'Area_Staging_P'    => $params['Area_Staging_P'] ?? null,
                'Area_Staging_L'    => $params['Area_Staging_L'] ?? null,
                'Area_Staging_T'    => $params['Area_Staging_T'] ?? null,
                'Area_Loading_P'    => $params['Area_Loading_P'] ?? null,
                'Area_Loading_L'    => $params['Area_Loading_L'] ?? null,
                'Alamat'            => $params['Alamat'] ?? null,
                'Demand_DO'         => $params['Demand_DO'] ?? null,
                'Capacity_DO'       => $params['Capacity_DO'] ?? null,
                'Capacity_CBM'      => $params['Capacity_CBM'] ?? null,
                'NIK_Leader'        => $params['NIK_Leader'] ?? null,
                'Telp'              => $params['Telp'] ?? null,
                'Background_Image'  => $params['Background_Image'] ?? null,
                'Staff_Up'          => $params['Staff_Up'] ?? null,
                'Driver'            => $params['Driver'] ?? null,
                'Asst_Driver'       => $params['Asst_Driver'] ?? null,
                'WHM'               => $params['WHM'] ?? null,
                'Security'          => $params['Security'] ?? null,
            ];

            $stmt->execute($bindParams);

            return $stmt->fetchAll(\PDO::FETCH_OBJ) ?? [];

        } catch (\Exception $e) {
            throw new \Exception("Error execSP ({$action}): " . $e->getMessage());
        }
    }

}
