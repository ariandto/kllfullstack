<?php
namespace App\Models\Admin\Report\Inbound;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ModelMonitoringReceieveBalikan extends Model
{

    public static function getReceieveData($facilityID, $StartDate, $EndDate, $Relasi, $notto, $sku, $loc, $lpn)
    {
        try {
            set_time_limit(1000);

            $connection = \DB::connection('sqlsrv')->getPdo();
            $query = "SET NOCOUNT ON; EXEC [Get_Data_Reveive_balikan_Web] ?,?,?,?,?,?,?,?,?";
            $stmt = $connection->prepare($query);
            $stmt->execute([
                'Get Receive data', $facilityID, $StartDate, $EndDate, $Relasi, $notto, $sku, $loc, $lpn
            ]);

            $results = [];
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Ambil hasil query
            $headers1 = array_keys($results[0][0] ?? []);

            // 🔹 Tambahkan Gambar dalam Bentuk Base64
            foreach ($results[0] as &$item) {
                $item['Foto1_base64'] = self::getFtpImageBase64($item['Foto1'] ?? null);
                $item['Foto2_base64'] = self::getFtpImageBase64($item['Foto2'] ?? null);
                $item['Foto3_base64'] = self::getFtpImageBase64($item['Foto3'] ?? null);
            }

            return [
                'tabel1' => $results[0] ?? [],
                'headers1' => $headers1,
            ];
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getStockData($facilityID, $StartDate, $EndDate, $Relasi, $notto, $sku, $loc, $lpn)
    {
        try {
            set_time_limit(300);

            $connection = \DB::connection('sqlsrv')->getPdo();
            $query = "SET NOCOUNT ON; EXEC [Get_Data_Reveive_balikan_Web] ?,?,?,?,?,?,?,?,?";
            $stmt = $connection->prepare($query);
            $stmt->execute([
                'Get Stock data', $facilityID, $StartDate, $EndDate, $Relasi, $notto, $sku, $loc, $lpn
            ]);

            $results = [];
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Ambil hasil query
            $headers1 = array_keys($results[0][0] ?? []);

            // 🔹 Tambahkan Gambar dalam Bentuk Base64
            foreach ($results[0] as &$item) {
                $item['Foto1_base64'] = self::getFtpImageBase64($item['Foto1'] ?? null);
                $item['Foto2_base64'] = self::getFtpImageBase64($item['Foto2'] ?? null);
                $item['Foto3_base64'] = self::getFtpImageBase64($item['Foto3'] ?? null);
            }

            return [
                'tabel1' => $results[0] ?? [],
                'headers1' => $headers1,
            ];
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    /**
     * Mengambil gambar dari FTP dan mengonversinya ke Base64
     */
    public static function getFtpImageBase64($filename)
    {
        if (!$filename) return null;

        // Path di FTP
        $path = "Attachment/BarangBalikan/{$filename}";

        // Cek apakah file ada
        if (Storage::disk('ftp')->exists($path)) {
            $fileContents = Storage::disk('ftp')->get($path);

            // Konversi ke Base64
            $base64 = base64_encode($fileContents);
            return 'data:image/jpeg;base64,' . $base64;
        }

        return null; // Jika file tidak ditemukan
    }
}
