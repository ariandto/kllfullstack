<?php

namespace App\Models\Admin\ApplicationRequest;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelAssignAR extends Model
{
    protected $connection = 'sqlsrv';
    public static function getEmpInfo($UserID, $Key1, $Key2, $Key3)
    {
        try {
            // dd($facilityID, $Relasi);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [AR_Get_Data] ?, ?, ?, ?, ?";
            $result = DB::select($query, ['Get Emp Info', $UserID, $Key1, $Key2, $Key3]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getlistApp($UserID, $Key1, $Key2, $Key3)
    {
        try {
            // dd($facilityID, $Relasi);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [AR_Get_Data] ?, ?, ?, ?, ?";
            $result = DB::select($query, ['Get List App', $UserID, $Key1, $Key2, $Key3]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getCompanyImpact($UserID, $Key1, $Key2, $Key3)
    {
        try {
            // dd($facilityID, $Relasi);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [AR_Get_Data] ?, ?, ?, ?, ?";
            $result = DB::select($query, ['Get Company Impact', $UserID, $Key1, $Key2, $Key3]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getDCPilot($UserID, $Key1, $Key2, $Key3)
    {
        try {
            // dd($facilityID, $Relasi);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [AR_Get_Data] ?, ?, ?, ?, ?";
            $result = DB::select($query, ['Get DC Pilot Project', $UserID, $Key1, $Key2, $Key3]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getSuperior($UserID, $Key1, $Key2, $Key3)
    {
        try {
            // dd($facilityID, $Relasi);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [AR_Get_Data] ?, ?, ?, ?, ?";
            $result = DB::select($query, ['Ger Superior', $UserID, $Key1, $Key2, $Key3]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getReqType($UserID, $Key1, $Key2, $Key3)
    {
        try {
            // dd($facilityID, $Relasi);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [AR_Get_Data] ?, ?, ?, ?, ?";
            $result = DB::select($query, ['Get Request Type', $UserID, $Key1, $Key2, $Key3]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getKLIP($UserID, $Key1, $Key2, $Key3)
    {
        try {
            // dd($facilityID, $Relasi);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [AR_Get_Data] ?, ?, ?, ?, ?";
            $result = DB::select($query, ['Get KLIP', $UserID, $Key1, $Key2, $Key3]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
}
