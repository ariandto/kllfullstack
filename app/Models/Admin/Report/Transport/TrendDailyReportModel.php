<?php

namespace App\Models\Admin\Report\Transport;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class TrendDailyReportModel extends Model
{
    public $timestamps = false;

    /**
     * 🔹 Ambil daftar site (dropdown)
     */
    public static function getSiteList()
    {
        try {
            $pdo = DB::getPdo();
            $stmt = $pdo->prepare("
                EXEC [udsp_Get_Data] 'Get Zone DC 3', ?, ?, 'NDC', 'ALL'
            ");
            $stmt->execute(['DC INFORMA JABABEKA', 'WMWHSE4RTL']);
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if (empty($rows)) {
                Log::warning('⚠️ Tidak ada hasil dari udsp_Get_Data');
                return [];
            }

            $firstKeys = array_keys($rows[0]);
            $columnName = collect($firstKeys)
                ->first(fn($c) => stripos($c, 'name') !== false) ?? $firstKeys[0];

            return collect($rows)
                ->map(fn($row) => ['Facility' => $row[$columnName] ?? 'Unknown'])
                ->unique('Facility')
                ->values()
                ->toArray();
        } catch (Throwable $e) {
            Log::error('❌ Error getSiteList: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * 🔹 Ambil data laporan transport harian (3 result set)
     */
    public static function getReportData($startDate, $endDate, $site)
    {
        $results = [
            'summary' => [],
            'olf' => [],
            'expense' => [],
        ];

        try {
            DB::connection()->disableQueryLog();
            ini_set('max_execution_time', 180);

            $pdo = DB::connection()->getPdo();

            $sql = "SET NOCOUNT ON; EXEC [dbo].[Report_Transport_Bar] ?, ?, ?, ?, ?";
            $params = ['', $startDate, $endDate, 'WMWHSE4RTL', $site];

            Log::info('🚀 Eksekusi SP Report_Transport_Bar', ['params' => $params]);

            try {
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
            } catch (\PDOException $pe) {
                Log::error('❌ Gagal EXEC SP langsung: ' . $pe->getMessage(), [
                    'sql' => $sql,
                    'params' => $params,
                ]);
                throw $pe;
            }

            $setIndex = 0;
            do {
                try {
                    $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    if (!empty($rows)) {
                        // 🧹 Bersihkan nama kolom & nilai
                        $cleanRows = array_map(fn($r) => self::sanitizeRow($r), $rows);

                        Log::info("📦 Result set ke-$setIndex ditemukan", [
                            'rows' => count($rows),
                            'columns' => array_keys($rows[0]),
                        ]);

                        match ($setIndex) {
                            0 => $results['summary'] = $cleanRows,
                            1 => $results['olf']     = $cleanRows,
                            2 => $results['expense'] = $cleanRows,
                            default => null,
                        };
                        $setIndex++;
                    }
                } catch (\PDOException $e) {
                    if (stripos($e->getMessage(), 'contains no fields') !== false) {
                        Log::warning('⚠️ Dummy result set terdeteksi, loop dihentikan');
                        break;
                    } else {
                        Log::error('❌ Error fetchAll: ' . $e->getMessage());
                        throw $e;
                    }
                }
            } while ($stmt->nextRowset());

            Log::info('✅ Semua result set berhasil dibaca', ['total_sets' => $setIndex]);

            return $results;

        } catch (Throwable $e) {
            Log::error('❌ Error getReportData Model (FINAL): ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'params' => compact('startDate', 'endDate', 'site'),
            ]);

            return $results;
        }
    }

    /**
     * 🧹 Bersihkan nama kolom & nilai agar JSON aman
     */
    private static function sanitizeRow($row)
    {
        $cleanRow = [];

        foreach ($row as $key => $value) {
            // 🔧 Ubah karakter aneh di nama kolom jadi underscore
            // contoh: "UJP/DropPoint" → "UJP_DropPoint", "% Pending" → "_Pending"
            $cleanKey = preg_replace('/[^\w\-]+/u', '_', $key);

            // Bersihkan nilai string dari karakter kontrol
            if (is_string($value)) {
    // hapus semua illegal JSON chars, extended ASCII, control bytes
    $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
    $value = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $value);
}


            // Hindari duplikasi key
            if (isset($cleanRow[$cleanKey])) {
                $cleanKey .= '_dup';
            }

            $cleanRow[$cleanKey] = $value;
        }

        return $cleanRow;
    }
}
