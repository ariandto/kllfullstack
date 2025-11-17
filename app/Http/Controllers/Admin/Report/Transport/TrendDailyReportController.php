<?php

namespace App\Http\Controllers\Admin\Report\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Report\Transport\TrendDailyReportModel;
use Illuminate\Support\Facades\Log;

class TrendDailyReportController extends Controller
{
    /**
     * 🔹 Ambil daftar site (dropdown)
     */
    public function getSites()
    {
        try {
            $data = TrendDailyReportModel::getSiteList();

            if (empty($data)) {
                Log::warning('⚠️ Tidak ada data site ditemukan.');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada data site ditemukan.',
                    'data' => [],
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);

        } catch (\Throwable $e) {

            Log::error('❌ Error getSites: ' . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data site.',
            ], 500);
        }
    }


    /**
     * 🔹 Ambil data laporan transport harian
     */
    public function getReportData(Request $request)
    {
        $startTime = microtime(true);

        try {

            $startDate = $request->input('startDate');
            $endDate   = $request->input('endDate');
            $site      = $request->input('site');

            Log::info('📥 Request getReportData diterima', [
                'startDate' => $startDate,
                'endDate'   => $endDate,
                'site'      => $site
            ]);

            /**
             * Cek range tanggal (hindari SP tidak mengembalikan data)
             */
            if ($startDate > $endDate) {
                Log::warning('⚠️ Range tanggal tidak valid');
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Start date tidak boleh lebih besar dari end date.'
                ], 400);
            }

            /**
             * Jalankan model
             */
            $results  = TrendDailyReportModel::getReportData($startDate, $endDate, $site);

            $summary  = $results['summary'] ?? [];
            $olf      = $results['olf'] ?? [];
            $expense  = $results['expense'] ?? [];

            $totalRows = count($summary) + count($olf) + count($expense);

            if ($totalRows === 0) {

                Log::warning('⚠️ SP tidak mengembalikan data', [
                    'startDate' => $startDate,
                    'endDate'   => $endDate,
                    'site'      => $site
                ]);

                return response()->json([
                    'status'  => 'success',
                    'message' => 'Tidak ada data dikembalikan dari server.',
                    'data'    => [
                        'summary' => [],
                        'olf'     => [],
                        'expense' => []
                    ]
                ], 200);
            }


            /**
             * Bersihkan data baris demi baris
             */
            $cleanSummary = [];
            foreach ($summary as $row) {
                $cleanSummary[] = $this->sanitizeRow($row);
            }

            $cleanOlf = [];
            foreach ($olf as $row) {
                $cleanOlf[] = $this->sanitizeRow($row);
            }

            $cleanExpense = [];
            foreach ($expense as $row) {
                $cleanExpense[] = $this->sanitizeRow($row);
            }

            $execution = round(microtime(true) - $startTime, 3);

            Log::info('✅ getReportData siap mengirim response', [
                'rows_summary' => count($cleanSummary),
                'rows_olf'     => count($cleanOlf),
                'rows_expense' => count($cleanExpense),
                'execution'    => $execution . 's'
            ]);


            /**
             * Test JSON encode SEBELUM dikirim (agar tidak silent fail)
             */
            $payload = [
                'status'         => 'success',
                'message'        => 'Data laporan berhasil diambil.',
                'execution_time' => $execution,
                'data'           => [
                    'summary' => $cleanSummary,
                    'olf'     => $cleanOlf,
                    'expense' => $cleanExpense,
                ],
            ];

            $test = json_encode($payload);

            if ($test === false) {
                Log::error('❌ JSON encoding gagal', [
                    'json_error' => json_last_error_msg(),
                ]);
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Gagal encoding JSON (data tidak valid di salah satu kolom).'
                ], 500);
            }

            return response()->json(
                $payload,
                200,
                [],
                JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR
            );


        } catch (\Throwable $e) {

            Log::error('❌ Error getReportData Controller (FINAL): ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memuat data laporan.',
            ], 500);
        }
    }


    /**
     * 🔧 Sanitasi baris data
     */
    private function sanitizeRow($row)
    {
        $clean = [];

        foreach ($row as $key => $value) {

            $cleanKey = preg_replace('/[^\w\-]+/u', '_', $key);

            if (is_string($value)) {

                // perbaikan sanitasi karakter rusak
                $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                $value = preg_replace('/[\x00-\x1F\x7F\x80-\xFF]/', '', $value);
            }

            $clean[$cleanKey] = $value;
        }

        return $clean;
    }
}
