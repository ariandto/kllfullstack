<?php

namespace App\Models\Admin\ApplicationRequest;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelTaskAR extends Model
{
    protected $connection = 'sqlsrv';
    public static function getTotalTask($UserID, $Key1, $Key2, $Key3)
    {
        try {
            // dd($facilityID, $Relasi);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [AR_Get_Data] ?, ?, ?, ?, ?";
            $result = DB::select($query, ['Get Total Task', $UserID, $Key1, $Key2, $Key3]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getTaskView($UserID, $Key1)
    {
        try {
            // dd($facilityID, $Relasi);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [AR_Task_Data] ?, ?, ?";
            $result = DB::select($query, ['Task View', $UserID, $Key1]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function postGetSubmitDeveloper()
    {
        try {
            // dd($facilityID, $Relasi);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [AR_Get_Data] ?, ?, ? ,? , ?";
            $result = DB::select($query, ['Get Developer', '', '', '', '',]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getCompanyImpact($ApplicationNo)
    {
        try {
            // dd($facilityID, $Relasi);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [AR_Get_Data] ?, ?, ? ,? , ?";
            $result = DB::select($query, ['Get Company Impact Analyst', $ApplicationNo, '', '', '',]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function postAssignAR($ApplicationNo, $DataAnalyst, $DataDeveloper, $GoLive, $Comment, $userID, $DataCompanyImpact)
    {
        try {
            // dd($facilityID, $Relasi);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [AssignApplicationRequest] ?, ?, ? ,? , ? , ? , ?";
            $result = DB::select($query, [$ApplicationNo, $DataAnalyst, $DataDeveloper, $GoLive, $Comment, $userID, $DataCompanyImpact]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }



    public static function postGetSubmitDetailDeveloper($NIK)
    {
        try {
            // dd($facilityID, $Relasi);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [AR_Get_Data] ?, ?, ? ,? , ?";
            $result = DB::select($query, ['Detail Avaibility', $NIK, '', '', '',]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
    public static function postSubmitSuperiordanSupernya($ArNo, $UserID, $Comment, $ButtonName)
    {
        try {
            // dd($facilityID, $Relasi);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [UpdateStatusApplicationRequest] ?, ?, ? , ?";
            $result = DB::statement($query, [$ArNo, $UserID, $Comment, $ButtonName]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getTaskViewDetail($UserID, $Key1)
    {
        try {
            set_time_limit(300);

            // Menggunakan koneksi yang ditentukan
            $connection = DB::connection('sqlsrv')->getPdo();
            // Menyiapkan query untuk mengeksekusi stored procedure
            $query = "SET NOCOUNT ON; EXEC [AR_Task_Data] ?,?,?";
            $stmt = $connection->prepare($query);
            $stmt->execute([
                'Task Detail',
                $UserID,
                $Key1
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
