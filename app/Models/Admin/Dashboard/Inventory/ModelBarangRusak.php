<?php

namespace App\Models\Admin\Dashboard\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelBarangRusak extends Model
{
    use HasFactory;

    public static function getSummaryData($facilityID, $Relasi, $StartDate, $EndDate, $Owner, $Storerkey, $Key1, $Key2, $Key3, $Key4, $Key5)
    {
        try {

            // dd([
            //     'facilityID' => $facilityID,
            //     'Relasi' => $Relasi,
            //     'StartDate' => $StartDate,
            //     'EndDate' => $EndDate,
            //     'Owner' => $Owner,
            //     'Storerkey' => $Storerkey,
            //     'Key1' => $Key1,
            //     'Key2' => $Key2,
            //     'Key3' => $Key3,
            // ]);

            // Mengatur maksimal waktu eksekusi menjadi 180 detik (3 menit)
            set_time_limit(300);

            // Menggunakan koneksi yang ditentukan
            $connection = DB::connection('sqlsrv')->getPdo();

            // Menyiapkan query untuk mengeksekusi stored procedure
            $query = "SET NOCOUNT ON; EXEC [Rizki_Udsp_Monitoring_ADF] ?, ?, ?, ?, ?, ?, ?, ?, ?, ? , ? ,?";
            $stmt = $connection->prepare($query);
            $stmt->execute([
                'Get Summary',
                $facilityID,
                $Relasi,
                $Storerkey,
                $Owner,
                $StartDate,
                $EndDate,
                $Key1,
                $Key2,
                $Key3,
                $Key4,
                $Key5
            ]);
            // $debugQuery = sprintf(
            //     "EXEC [Rizki_Udsp_Monitoring_ADF] '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'",
            //     'Get Summary', $facilityID, $Relasi, $Storerkey, $Owner, $StartDate, $EndDate, $Key1, $Key2, $Key3
            // );

            // // Gunakan dd() untuk melihat query di layar
            // dd($debugQuery); 
            // Mengambil hasil dari kedua tabel
            $results = [];
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel pertama
            $stmt->nextRowset(); // Pindah ke rowset kedua
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel kedua
            $stmt->nextRowset(); // Pindah ke rowset ketiga
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel ketiga

            $headers1 = array_keys($results[0][0] ?? []); // Header untuk tabel pertama
            $headers2 = array_keys($results[1][0] ?? []); // Header untuk tabel kedua
            $headers3 = array_keys($results[2][0] ?? []); // Header untuk tabel kedua


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
}


     // dd([
            //     'tabel1' => $results[0] ?? [],
            //     'tabel2' => $results[1] ?? [],
            //     'headers1' => $headers1,
            //     'headers2' => $headers2,
            // ]);