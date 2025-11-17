<?php

namespace App\Models\Admin\ApplicationRequest;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelCreateAR extends Model
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

    public static function getARNumber($ApplicationRequest, $UserID, $Company, $JobTtlName, $OrganizationName, $Email, $ProjectName, $PilotProject, $CompanyImpact, $ProjectOwner, $Superior, $RequestType, $ApplicationType, $GotoKLIP, $KLIPNumber, $ExpectedGolive, $Point1, $Point2, $Point3, $Point4, $Attachment, $ButtonName, $Comment, $Raci)
    {
        try {
            //dd($ApplicationRequest, $UserID, $Company, $JobTtlName, $OrganizationName, $Email, $ProjectName, $PilotProject, $CompanyImpact, $ProjectOwner, $Superior, $RequestType, $ApplicationType, $GotoKLIP, $KLIPNumber, $ExpectedGolive, $Point1, $Point2, $Point3, $Point4, $Attachment, $ButtonName, $Comment);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [InsertApplicationRequest] ?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?, ?,?,?,?,?";
            $result = DB::select($query, [$ApplicationRequest, $UserID, $Company, $JobTtlName, $OrganizationName, $Email, $ProjectName, $PilotProject, $CompanyImpact, $ProjectOwner, $Superior, $RequestType, $ApplicationType, $GotoKLIP, $KLIPNumber, $ExpectedGolive, $Point1, $Point2, $Point3, $Point4, $Attachment, $ButtonName, $Comment, $Raci]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }


    public static function getSubmitAR($ApplicationRequest, $UserID, $Company, $JobTtlName, $OrganizationName, $Email, $ProjectName, $PilotProject, $CompanyImpact, $ProjectOwner, $Superior, $RequestType, $ApplicationType, $GotoKLIP, $KLIPNumber, $ExpectedGolive, $Point1, $Point2, $Point3, $Point4, $Attachment, $ButtonName, $Comment, $Raci)
    {
        try {
            //dd($ApplicationRequest, $UserID, $Company, $JobTtlName, $OrganizationName, $Email, $ProjectName, $PilotProject, $CompanyImpact, $ProjectOwner, $Superior, $RequestType, $ApplicationType, $GotoKLIP, $KLIPNumber, $ExpectedGolive, $Point1, $Point2, $Point3, $Point4, $Attachment, $ButtonName, $Comment);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [InsertApplicationRequest] ?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?, ?,?,?,?,?";
            $result = DB::select($query, [$ApplicationRequest, $UserID, $Company, $JobTtlName, $OrganizationName, $Email, $ProjectName, $PilotProject, $CompanyImpact, $ProjectOwner, $Superior, $RequestType, $ApplicationType, $GotoKLIP, $KLIPNumber, $ExpectedGolive, $Point1, $Point2, $Point3, $Point4, $Attachment, $ButtonName, $Comment, $Raci]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getARDraft($UserID, $Key1, $Key2, $Key3)
    {
        try {
            set_time_limit(300);

            // Menggunakan koneksi yang ditentukan
            $connection = DB::connection('sqlsrv')->getPdo();
            // Menyiapkan query untuk mengeksekusi stored procedure
            $query = "SET NOCOUNT ON; EXEC [AR_Get_Data] ?,?,?,?,?";
            $stmt = $connection->prepare($query);
            $stmt->execute([
                'Get Data AR Draft',
                $UserID,
                $Key1,
                $Key2,
                $Key3
            ]);

            $results = [];
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel pertama
            $stmt->nextRowset(); // Pindah ke rowset kedua
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel kedua
            // $stmt->nextRowset(); // Pindah ke rowset ketiga
            // $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel ketiga

            $headers1 = array_keys($results[0][0] ?? []); // Header untuk tabel pertama
            $headers2 = array_keys($results[1][0] ?? []); // Header untuk tabel kedua
            // $headers3 = array_keys($results[2][0] ?? []); // Header untuk tabel kedua


            return [
                'tabel1' => $results[0] ?? [],
                'tabel2' => $results[1] ?? [],
                'headers1' => $headers1,
                'headers2' => $headers2,

            ];
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
}
