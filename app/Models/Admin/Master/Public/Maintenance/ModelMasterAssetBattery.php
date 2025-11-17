<?php

namespace App\Models\Admin\Master\Public\Maintenance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelMasterAssetBattery extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv';
    public static function getTypeUnit()
    {
        try {
            // Ensure there are no unclosed quotes or misplaced characters
            $query = 'SET NOCOUNT ON; EXEC [Web_Get_Data] ?, ?,?,?,?';
            return DB::select($query, ['Get Related Unit Type', '', '', '','']);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getData($facilityID, $Relasi, $Owner, $AssetNo, $UnitNo)
    {
        try {
            // Ensure there are no unclosed quotes or misplaced characters
            $query = 'SET NOCOUNT ON; EXEC [SPMasterAssetBattery] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?';
            return DB::select($query, ['Get Data', $facilityID, $Relasi, $Owner, $AssetNo, $UnitNo, '', '', '', '', '', '', '', '', '']);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getDataView($facilityID, $Relasi, $Owner)
    {
        try {
            // Ensure there are no unclosed quotes or misplaced characters
            $query = 'SET NOCOUNT ON; EXEC [SPMasterAssetBattery] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?';
            return DB::select($query, ['Data View', $facilityID, $Relasi, $Owner, '', '', '', '', '', '', '', '', '', '', '']);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function insertDataView($facilityID, $Relasi, $Owner, $AssetNo, $UnitNo, $UnitName, $UnitType, $ReceiveDate, $LifeTime, $IsActive, $AddUser, $Brand, $BatteryType, $RelatedUnitType)
    {
        try {
            //dd($$facilityID,$Relasi,$Owner,$Dept,$Shift, $Start1, $End1, $Start2, $End2);
            $query = 'SET NOCOUNT ON; EXEC [SPMasterAssetBattery] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?';
            return DB::statement($query, ['Insert', $facilityID, $Relasi, $Owner, $AssetNo, $UnitNo, $UnitName, $UnitType, $ReceiveDate, $LifeTime, $IsActive, $AddUser, $Brand, $BatteryType, $RelatedUnitType]);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function deleteDataView($facilityID, $Relasi, $Owner, $AssetNo, $UnitNo, $UnitName, $UnitType, $ReceiveDate, $LifeTime, $IsActive, $AddUser, $Brand, $BatteryType, $RelatedUnitType)
    {
        try {
            //dd($$facilityID,$Relasi,$Owner,$Dept,$Shift, $Start1, $End1, $Start2, $End2);
            $query = 'SET NOCOUNT ON; EXEC [SPMasterAssetBattery] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?';
            return DB::statement($query, ['Delete', $facilityID, $Relasi, $Owner, $AssetNo, $UnitNo, $UnitName, $UnitType, $ReceiveDate, $LifeTime, $IsActive, $AddUser, $Brand, $BatteryType, $RelatedUnitType]);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
}
