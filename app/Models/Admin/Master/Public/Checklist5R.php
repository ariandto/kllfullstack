<?php

namespace App\Models\Admin\Master\Public;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Checklist5R extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv';
    public static function getDataView($facilityID,$Relasi,$Shift, $Start1, $End1, $Start2, $End2)
    {
        try {
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [udsp_MasterShiftPoint5R] ?, ?,?, ?, ?, ?, ?, ?, ?,?";
            return DB::select($query, ['Data View',$facilityID,$Relasi,'','',$Shift, $Start1, $End1, $Start2, $End2]);
        } catch (\Exception $e) {
          
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function insertDataView($facilityID,$Relasi,$Owner,$Dept,$Shift, $Start1, $End1, $Start2, $End2)
    {
        try {
            //dd($$facilityID,$Relasi,$Owner,$Dept,$Shift, $Start1, $End1, $Start2, $End2);
            $query = "SET NOCOUNT ON; EXEC [udsp_MasterShiftPoint5R] ?, ?,?, ?, ?, ?, ?, ?, ?,?";
            return DB::statement($query, ['Insert',$facilityID,$Relasi,$Owner,$Dept,$Shift, $Start1, $End1, $Start2, $End2]);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function deleteDataView($facilityID,$Relasi,$Owner,$Dept,$Shift, $Start1, $End1, $Start2, $End2)
    {
        try {
           //dd($facilityID,$Relasi,$Owner,$Dept,$Shift, $Start1, $End1, $Start2, $End2);
            $query = "SET NOCOUNT ON; EXEC [udsp_MasterShiftPoint5R] ?, ?,?, ?, ?, ?, ?, ?, ?,?";
            return DB::statement($query, ['Delete',$facilityID,$Relasi,$Owner,$Dept,$Shift, $Start1, $End1, $Start2, $End2]);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }




    //Pemisah Ya  Disini

    public static function insertDataViewPoint5R($facilityID,$Relasi,$Owner,$Dept, $Area, $PointCheck, $Point1, $Point2, $Point3, $Point4 )
    {
        try {
            //dd($facilityID,$Relasi,$Shift, $Start1, $End1, $Start2, $End2);
            $query = "SET NOCOUNT ON; EXEC [udsp_MasterPoint5R] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
            return DB::statement($query, ['Insert',$facilityID,$Owner,$Relasi,$Dept,$Area,$PointCheck,$Point1, $Point2, $Point3, $Point4]);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function updateDataViewPoint5R($whseid,$owner,$relasi, $dept_name,$area , $point_check, $point1, $point2, $point3, $point4 )
    {
        try {
            //dd($whseid,$owner,$relasi, $dept_name,$area , $point_check, $point1, $point2, $point3, $point4);
            $query = "SET NOCOUNT ON; EXEC [udsp_MasterPoint5R] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
            return DB::statement($query, ['Insert',$whseid,$owner,$relasi, $dept_name, $area, $point_check, $point1, $point2, $point3, $point4 ]);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }


    public static function deleteDataViewPoint5R($whseid,  $owner, $Relasi, $deptName, $area, $pointCheck)
    {
        try {
            //dd($whseid,  $owner, $Relasi, $deptName, $area, $pointCheck );
            $query = "SET NOCOUNT ON; EXEC [udsp_MasterPoint5R] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
            return DB::statement($query, ['Delete',$whseid,  $owner, $Relasi, $deptName, $area, $pointCheck,'', '', '', '', ]);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getDataViewPoint5R($facilityID,$Relasi)
    {
        try {
            //dd($facilityID,$Relasi,$Shift, $Start1, $End1, $Start2, $End2);
            $query = "SET NOCOUNT ON; EXEC [udsp_MasterPoint5R] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
            return DB::select($query, ['Data View',$facilityID,'',$Relasi,'','','','','','','']);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }


   
}

