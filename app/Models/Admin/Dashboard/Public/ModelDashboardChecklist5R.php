<?php

namespace App\Models\Admin\Dashboard\Public;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelDashboardChecklist5R extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv';

    public static function getDataView($facilityID,$Relasi,$Owner, $StartDate, $EndDate, $Dept, $Area, $Status)
    {
       
        try {
            set_time_limit(300);
            
           // dd($facilityID,$Relasi,$Owner, $StartDate, $EndDate, $Dept, $Area, $Status);
            // $OwnerString = implode(';', $Owner);
        
            // Menggunakan koneksi yang ditentukan
            $connection = DB::connection('sqlsrv')->getPdo();
            
            // Menyiapkan query untuk mengeksekusi stored procedure
            $query = "SET NOCOUNT ON; EXEC [udsp_Dashboard5R] ?, ?, ?, ?, ?, ?, ?, ?";
            $stmt = $connection->prepare($query);
            $stmt->execute([
                $facilityID, $Relasi, $Owner, $StartDate, $EndDate, $Dept, $Area, $Status
            ]);
        
            // Mengambil hasil dari ketiga tabel
            $results = [];
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel pertama
            $stmt->nextRowset(); // Pindah ke rowset kedua
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel kedua
            $stmt->nextRowset(); // Pindah ke rowset ketiga
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel ketiga
        
            // Mengambil header untuk masing-masing tabel
            $headers1 = array_keys($results[0][0] ?? []); // Header untuk tabel pertama
            $headers2 = array_keys($results[1][0] ?? []); // Header untuk tabel kedua
            $headers3 = array_keys($results[2][0] ?? []); // Header untuk tabel ketiga
            
            // dd($results[0] ?? [],$results[1] ?? [],$results[2] ?? []);

            return [
                'tabel1' => $results[0] ?? [],
                'tabel2' => $results[1] ?? [],
                'tabel3' => $results[2] ?? [],
                'headers1' => $headers1,
                'headers2' => $headers2,
                'headers3' => $headers3,
            ];
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getDataDetailPie($facilityID,$Relasi,$Owner, $StartDate, $EndDate, $Dept, $Area, $Status)
    {
        //dd($facilityID,$Relasi,$Owner, $StartDate, $EndDate, $Dept, $Area, $Status);
        try {
            set_time_limit(300);
            $connection = DB::connection('sqlsrv')->getPdo();
            $query = "SET NOCOUNT ON; EXEC [udsp_Dashboard5R_Detail] ?, ?, ?, ?, ?, ?, ?, ?";
            $stmt = $connection->prepare($query);
            $stmt->execute([
                $facilityID, $Relasi, $Owner, $StartDate, $EndDate, $Dept, $Area, $Status
            ]);
            $results = [];
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel pertama
            $headerspie = array_keys($results[0][0] ?? []); // Header untuk tabel pertama
         
            return [
                'tabelpie' => $results[0] ?? [], 
                'headerspie' => $headerspie, 
            ];
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }   
}

