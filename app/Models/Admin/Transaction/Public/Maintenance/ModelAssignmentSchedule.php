<?php

namespace App\Models\Admin\Transaction\Public\Maintenance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelAssignmentSchedule extends Model
{
    protected $connection = 'sqlsrv';
    public static function GetUnitTypeAssetDC($facilityID, $Relasi, $Key1, $Key2)
    {
        try {
            // dd($facilityID, $Relasi);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [Mobile_Get_Data] ?, ?, ?, ?, ?";
            $result = DB::select($query, ['Get Unit Type Asset DC', $facilityID, $Relasi, $Key1, $Key2]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function GetEmployeeMaintenance($facilityID, $Relasi, $Key1, $Key2)
    {
        try {
            // dd($facilityID, $Relasi);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [Mobile_Get_Data] ?, ?, ?, ?, ?";
            $result = DB::select($query, ['Get Employee Maintenance', $facilityID, $Relasi, $Key1, $Key2]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function GetDailyAssignment($facilityID, $Relasi, $Key1, $Key2)
    {
        try {
            //dd($facilityID, $Relasi, $Key1, $Key2);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [Web_Get_Data] ?, ?, ?, ?, ?";
            $result = DB::select($query, ['Daily Assignment', $facilityID, $Relasi, $Key1, $Key2]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
    public static function InsertDailyAssignment($facilityID, $Relasi, $Pic, $Teammate, $Activity, $Datastring, $userID)
    {
        try {
            // $sqlDebug = "EXEC [Insert_Daily_Assignment_Schedule_MHE] "
            //     . "'" . str_replace("'", "''", $facilityID) . "', "
            //     . "'" . str_replace("'", "''", $Relasi) . "', "
            //     . "'" . str_replace("'", "''", $Pic) . "', "
            //     . "'" . str_replace("'", "''", $Teammate) . "', "
            //     . "'" . str_replace("'", "''", $Activity) . "', "
            //     . "'" . str_replace("'", "''", $Datastring) . "', "
            //     . "'" . str_replace("'", "''", $userID) . "'";

            // Output ke log file atau dd() atau echo
            //dd($sqlDebug);
            // atau
            // dd($sqlDebug); // untuk sementara debug

            $query = "SET NOCOUNT ON; EXEC [Insert_Daily_Assignment_Schedule_MHE] ?, ?, ?, ?, ?, ?, ?";
            $result = DB::statement($query, [$facilityID, $Relasi, $Pic, $Teammate, $Activity, $Datastring, $userID]);

            return $result;
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }


    public static function GetActivityAssignment($facilityID, $Relasi, $Key1, $Key2)
    {
        try {
            // dd($facilityID, $Relasi);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [Web_Get_Data] ?, ?, ?, ?, ?";
            $result = DB::select($query, ['Get Activity Assignment', $facilityID, $Relasi, $Key1, $Key2]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }


    // exec [Web_Get_Data] 'Daily Assignment','wmwhse5','wmwhse5rtl','PALLET MOVER PANJANG','2025-04'
}
