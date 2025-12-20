<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentResponse;
use App\Models\Indicator; // Tambahkan ini
use App\Models\Domain;    // Tambahkan ini
use Barryvdh\DomPDF\Facade\Pdf; // Tambahkan ini untuk memperbaiki error 'Pdf'
use Illuminate\Http\Request;
use App\Http\Requests\StoreAssessmentRequest;
// use App\Helpers\IndicatorMapper; // Hapus atau komentari ini

class AssessmentController extends Controller
{
    /**
     * Store a newly created assessment with responses and documents
     * Handles multipart/form-data with file uploads
     */
    public function store(StoreAssessmentRequest $request)
    {
        $validated = $request->validated();

        // 1. Buat record Assessment awal
        $assessment = Assessment::create([
            'org_name' => $validated['org_name'],
            'org_type' => $validated['org_type'],
            'assessor_name' => $validated['assessor_name'],
            'assessor_position' => $validated['assessor_position'],
            'total_score' => 0, // Akan diupdate nanti
            'maturity_level' => 'Initial',
            'status' => 'completed',
            'assessment_date' => $validated['assessment_date'] ?? now(),
        ]);

        // 2. Simpan semua respon dan kumpulkan skor per subdomain
        $scoresBySubdomain = [];
        foreach ($validated['responses'] as $resp) {
            $indicator = Indicator::find($resp['indicator_id']);

            if ($indicator) {
                AssessmentResponse::create([
                    'assessment_id' => $assessment->id,
                    'indicator_id' => $resp['indicator_id'],
                    'score' => $resp['score'],
                    'evidence_text' => $resp['evidence_text'] ?? null,
                ]);

                // Kelompokkan skor untuk perhitungan rata-rata subdomain
                $scoresBySubdomain[$indicator->subdomain_id][] = $resp['score'];
            }
        }

        // 3. Hitung Skor Akhir Terbobot
        $overallScore = 0;
        $domains = Domain::with('subdomains')->get();

        foreach ($domains as $domain) {
            $domainScore = 0;
            $subdomainCountInDomain = $domain->subdomains->count();

            if ($subdomainCountInDomain > 0) {
                $sumSubdomainAverages = 0;
                foreach ($domain->subdomains as $subdomain) {
                    $subScores = $scoresBySubdomain[$subdomain->id] ?? [0];
                    $subAverage = array_sum($subScores) / count($subScores);
                    $sumSubdomainAverages += $subAverage;
                }

                // Rata-rata skor domain
                $domainAverage = $sumSubdomainAverages / $subdomainCountInDomain;
                // Kalikan dengan bobot domain (misal 20% = 0.2)
                $overallScore += ($domainAverage * $domain->weight / 100);
            }
        }

        // 4. Update hasil akhir ke record assessment
        $assessment->update([
            'total_score' => round($overallScore, 2),
            'maturity_level' => $this->calculateMaturityLevel($overallScore),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Assessment berhasil disimpan',
            'assessment_id' => $assessment->id,
            'total_score' => $assessment->total_score,
            'maturity_level' => $assessment->maturity_level
        ]);
    }

    /**
     * Calculate maturity level based on total score
     */
    private function calculateMaturityLevel($score)
    {
        if ($score < 2) return 'Initial';
        if ($score < 3) return 'Managed';
        if ($score < 4) return 'Defined';
        if ($score < 5) return 'Quantitatively Managed';
        return 'Optimizing';
    }

    /**
     * Get assessment details by ID
     */
    public function show(string $id)
    {
        try {
            $assessment = Assessment::with('responses')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $assessment,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Assessment not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Export assessment as PDF
     */
    public function exportPdf(string $id)
    {
        try {
            // Eager load responses untuk performa lebih baik
            $assessment = Assessment::with('responses')->findOrFail($id);

            // Ambil semua nama indikator dari database
            $indicators = Indicator::pluck('name', 'id')->toArray();

            // Prepare data for PDF view
            $data = [
                'assessment' => $assessment,
                'indicators' => $indicators(),
            ];

            // Generate PDF
            $pdf = Pdf::loadView('assessment.pdf-report', $data);
            $filename = "Assessment_{$assessment->org_name}_{$assessment->id}.pdf";

            return $pdf->download($filename);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export PDF',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export assessment as Excel (CSV format)
     */
    public function exportExcel(string $id)
    {
        try {
            $assessment = Assessment::with('responses')->findOrFail($id);

            // Create Excel export object
            $export = new \App\Exports\AssessmentExport($assessment);
            $data = $export->toArray();

            // Generate filename
            $filename = "Assessment_Summary_{$assessment->id}.csv";

            // Set headers for CSV download
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'max-age=0',
            ];

            // Create CSV response
            $callback = function () use ($data) {
                $file = fopen('php://output', 'w');

                // Add BOM for Excel UTF-8 support
                fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

                foreach ($data as $row) {
                    fputcsv($file, $row);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export Excel',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
