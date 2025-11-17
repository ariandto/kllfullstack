<?php

namespace App\Models\Admin\Master\Config;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelMasterActivityMaintenance extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv';
    public static function getTaskCategory()
    {
        try {
            // Ensure there are no unclosed quotes or misplaced characters
            $query = 'SET NOCOUNT ON; EXEC [Web_Get_Data] ?, ?,?,?,?';
            return DB::select($query, ['Get Task Category', '', '', '', '']);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getRelatedUnitType()
    {
        try {
            // Ensure there are no unclosed quotes or misplaced characters
            $query = 'SET NOCOUNT ON; EXEC [Web_Get_Data] ?, ?,?,?,?';
            return DB::select($query, ['Get Related Unit Type', '', '', '', '']);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getDataView()
    {
        try {
            // Ensure there are no unclosed quotes or misplaced characters
            $query = 'SET NOCOUNT ON; EXEC [SPMasterMHEActivity] ?';
            return DB::select($query, ['Data View']);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function insertDataView($description, $unitType, $taskCategory, $lamaPengerjaan, $uom, $isActive, $addUser)
    {
        try {
            $query = 'SET NOCOUNT ON; EXEC [SPMasterMHEActivity] ?, ?, ?, ?, ?, ?, ?, ?';
            return DB::statement($query, [
                'Insert', // @State
                $description, // @Description
                $unitType, // @UnitType
                $taskCategory, // @TaskCategory
                $lamaPengerjaan, // @LamaPengerjaan
                $uom, // @UOM
                $isActive, // @IsActive
                $addUser, // @AddUser
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function deleteDataView($description, $unitType, $taskCategory, $lamaPengerjaan, $uom, $isActive, $addUser)
    {
        try {
            $query = 'SET NOCOUNT ON; EXEC [SPMasterMHEActivity] ?, ?, ?, ?, ?, ?, ?, ?';
            return DB::statement($query, [
                'Delete', // @State
                $description, // @Description
                $unitType, // @UnitType
                $taskCategory, // @TaskCategory
                $lamaPengerjaan, // @LamaPengerjaan
                $uom, // @UOM
                $isActive, // @IsActive
                $addUser, // @AddUser
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
}
