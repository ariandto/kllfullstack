<?php

namespace App\Models\Admin\ApplicationRequest;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelListAR extends Model
{
    protected $connection = 'sqlsrv';

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

    public static function getStatusAR($UserID, $Key1, $Key2, $Key3)
    {
        try {
            // dd($facilityID, $Relasi);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [AR_Get_Data] ?, ?, ?, ?, ?";
            $result = DB::select($query, ['Get Status AR', $UserID, $Key1, $Key2, $Key3]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getDataView($StartDate, $EndDate, $AppType, $AppStatus)
    {
        try {
            // dd($facilityID, $Relasi);
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [AR_List_Data] ?, ?, ?, ?, ?";
            $result = DB::select($query, ['Data View', $StartDate, $EndDate, $AppType, $AppStatus]);

            return $result;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getDataViewDetail($StartDate, $EndDate, $AppType, $AppStatus)
    {
        try {
            set_time_limit(300);

            // Menggunakan koneksi yang ditentukan
            $connection = DB::connection('sqlsrv')->getPdo();
            // Menyiapkan query untuk mengeksekusi stored procedure
            $query = "SET NOCOUNT ON; EXEC [AR_Get_Data] ?, ?, ?, ?, ?";
            $stmt = $connection->prepare($query);
            $stmt->execute([
                'View AR',
                $StartDate,
                $EndDate,
                $AppType,
                $AppStatus
            ]);

            $results = [];
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel pertama
            // $stmt->nextRowset(); // Pindah ke rowset kedua
            // $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel kedua
            // $stmt->nextRowset(); // Pindah ke rowset ketiga
            // $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel ketiga

            $headers3 = array_keys($results[0][0] ?? []); // Header untuk tabel pertama
            // $headers2 = array_keys($results[1][0] ?? []); // Header untuk tabel kedua
            // $headers3 = array_keys($results[2][0] ?? []); // Header untuk tabel kedua


            return [
                'tabel3' => $results[0] ?? [],
                'headers3' => $headers3,

            ];
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
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
}
