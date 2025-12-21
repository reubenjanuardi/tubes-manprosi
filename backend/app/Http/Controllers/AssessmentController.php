<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentResponse;
use App\Helpers\IndicatorMapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\SpbePdfGenerator;
use App\Services\SpbeNarrativeGenerator;

class AssessmentController extends Controller
{
    /**
     * Store a newly created assessment with responses and documents
     * Handles multipart/form-data with file uploads
     */
    public function store(Request $request)
    {
        try {
            // Validate input data
            $validated = $request->validate([
                'org_name' => 'required|string|max:255',
                'org_type' => 'required|string|max:255',
                'assessor_name' => 'required|string|max:255',
                'assessor_position' => 'required|string|max:255',
                'assessment_date' => 'required|date',
                'responses' => 'required|array',
                'responses.*.indicator_id' => 'required|integer|between:1,32',
                'responses.*.score' => 'required|integer|between:1,5',
                'responses.*.evidence_text' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Calculate total score
            $scores = array_column($validated['responses'], 'score');
            $totalScore = count($scores) > 0 ? array_sum($scores) / count($scores) : 0;

            // Determine maturity level
            $maturityLevel = IndicatorMapper::getMaturityLevel($totalScore);

            // Create assessment record
            $assessment = Assessment::create([
                'org_name' => $validated['org_name'],
                'org_type' => $validated['org_type'],
                'assessor_name' => $validated['assessor_name'],
                'assessor_position' => $validated['assessor_position'],
                'assessment_date' => $validated['assessment_date'],
                'total_score' => $totalScore,
                'maturity_level' => $maturityLevel,
                'status' => 'completed',
            ]);

            // Create directory for evidence files
            $evidenceDir = "public/evidence/{$assessment->id}";
            if (!Storage::exists($evidenceDir)) {
                Storage::makeDirectory($evidenceDir);
            }

            // Store responses with file uploads
            foreach ($validated['responses'] as $index => $responseData) {
                $documentPath = null;

                // Check if file exists for this response index
                $fileKey = "responses.{$index}.file";
                if ($request->hasFile($fileKey)) {
                    $file = $request->file($fileKey);

                    // Validate file type and size
                    $allowedMimes = [
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'image/jpeg',
                        'image/png',
                        'image/jpg'
                    ];

                    if (!in_array($file->getMimeType(), $allowedMimes)) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid file type. Allowed: PDF, DOC, DOCX, JPG, PNG',
                        ], 422);
                    }

                    if ($file->getSize() > 5 * 1024 * 1024) { // 5MB max
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'File size exceeds 5MB limit',
                        ], 422);
                    }

                    // Store file
                    $filePath = $file->store("evidence/{$assessment->id}", 'public');
                    $documentPath = "/storage/{$filePath}";
                }

                // Create assessment response
                AssessmentResponse::create([
                    'assessment_id' => $assessment->id,
                    'indicator_id' => $responseData['indicator_id'],
                    'score' => $responseData['score'],
                    'evidence_text' => $responseData['evidence_text'] ?? null,
                    'document_path' => $documentPath,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Assessment saved successfully',
                'assessment_id' => $assessment->id,
                'total_score' => $assessment->total_score,
                'maturity_level' => $assessment->maturity_level,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to save assessment',
                'error' => $e->getMessage(),
            ], 500);
        }
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
        // Redirect to the SPBE PDF export method for consistent output
        return $this->exportSpbePdf($id);
    }

    /**
     * Export assessment as PDF with SPBE Government Style using TCPDF
     * 
     * @param string $id Assessment ID
     * @return \Illuminate\Http\Response
     */
    public function exportSpbePdf(string $id)
    {
        try {
            // Load assessment with related domains/responses
            $assessment = Assessment::with('responses')->findOrFail($id);

            // Get indicators
            $indicators = IndicatorMapper::getIndicators();

            // Group responses by domain and calculate domain scores
            $domainScores = [];
            $indikatorNilai = [];
            
            foreach ($assessment->responses as $response) {
                $indicatorId = $response->indicator_id;
                $indicatorName = $indicators[$indicatorId] ?? 'Indikator ' . $indicatorId;
                
                // Build indicator nilai array
                $indikatorNilai[] = [
                    'nama' => $indicatorName,
                    'nilai' => $response->score,
                ];
                
                // Extract domain from indicator
                $domainName = $this->extractDomainFromIndicator($indicatorName, $indicatorId);
                
                if (!isset($domainScores[$domainName])) {
                    $domainScores[$domainName] = [
                        'name' => $domainName,
                        'scores' => [],
                    ];
                }
                $domainScores[$domainName]['scores'][] = $response->score;
            }

            // Calculate average score per domain and build indeksSpbe
            $indeksSpbe = [];
            foreach ($domainScores as $domainName => $data) {
                $avgScore = count($data['scores']) > 0 
                    ? array_sum($data['scores']) / count($data['scores']) 
                    : 0;
                $indeksSpbe[] = [
                    'domain' => $data['name'],
                    'nilai' => $avgScore,
                ];
            }

            // If no domains found, create default domains from assessment
            if (empty($indeksSpbe)) {
                $indeksSpbe = [
                    ['domain' => 'Domain Kebijakan SPBE', 'nilai' => $assessment->total_score],
                    ['domain' => 'Domain Tata Kelola SPBE', 'nilai' => $assessment->total_score],
                    ['domain' => 'Domain Manajemen SPBE', 'nilai' => $assessment->total_score],
                    ['domain' => 'Domain Layanan SPBE', 'nilai' => $assessment->total_score],
                ];
            }

            // Build full institution name
            $fullInstitutionName = trim(($assessment->org_type ?? '') . ' ' . ($assessment->org_name ?? ''));
            if (empty($fullInstitutionName)) {
                $fullInstitutionName = $assessment->org_name ?? 'Instansi';
            }

            // Prepare base data for narrative generator
            $baseData = [
                'institution' => $fullInstitutionName,
                'year' => $assessment->assessment_date 
                    ? $assessment->assessment_date->format('Y') 
                    : date('Y'),
                'indeksSpbe' => $indeksSpbe,
            ];

            // Generate dynamic narrative content based on actual data
            $narrativeGenerator = new SpbeNarrativeGenerator($baseData);
            $narrativeContent = $narrativeGenerator->generateAllContent();

            // Prepare dasar hukum (static content)
            $dasarHukum = [
                'Peraturan Presiden Nomor 95 Tahun 2018 tentang Sistem Pemerintahan Berbasis Elektronik',
                'Peraturan Menteri PANRB Nomor 59 Tahun 2020 tentang Pemantauan dan Evaluasi SPBE',
                'Peraturan Menteri PANRB Nomor 5 Tahun 2018 tentang Pedoman Evaluasi Sistem Pemerintahan Berbasis Elektronik',
                'Keputusan Menteri PANRB tentang Hasil Evaluasi SPBE',
            ];

            // Generate academic project evaluation content
            $academicEvaluation = $narrativeGenerator->generateAcademicEvaluation();

            // Prepare data for PDF generation
            $data = [
                'institution' => $fullInstitutionName,
                'year' => $baseData['year'],
                'kataPengantar' => $narrativeContent['kataPengantar'],
                'ringkasan' => $narrativeContent['ringkasan'],
                'dasarHukum' => $dasarHukum,
                'metodologi' => $narrativeContent['metodologi'],
                'tingkatKematangan' => $narrativeContent['tingkatKematangan'],
                'indeksSpbe' => $indeksSpbe,
                'evaluasi' => $narrativeContent['evaluasi'],
                'rekomendasi' => $narrativeContent['rekomendasi'],
                'indikatorNilai' => $indikatorNilai,
                'evaluasiProyek' => $academicEvaluation,
            ];

            // Generate PDF using TCPDF
            $pdfGenerator = new SpbePdfGenerator($fullInstitutionName, $data['year']);
            $pdfContent = $pdfGenerator->generateReport($data);

            // Generate filename based on institution type and name
            $institutionType = $assessment->org_type ?? '';
            $institutionName = $assessment->org_name ?? '';
            $fullName = trim($institutionType . ' ' . $institutionName);
            $cleanName = preg_replace('/[^A-Za-z0-9\s\-]/', '', $fullName);
            $cleanName = preg_replace('/\s+/', '_', trim($cleanName));
            $filename = "Laporan_Hasil_Evaluasi_-_{$cleanName}.pdf";

            // Return PDF as download
            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Length', strlen($pdfContent));
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export SPBE PDF',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Extract domain name from indicator
     * 
     * @param string $indicatorName
     * @param int $indicatorId
     * @return string
     */
    private function extractDomainFromIndicator(string $indicatorName, int $indicatorId): string
    {
        // Map indicators to domains based on ID ranges
        if ($indicatorId >= 1 && $indicatorId <= 8) {
            return 'Domain Kebijakan SPBE';
        } elseif ($indicatorId >= 9 && $indicatorId <= 16) {
            return 'Domain Tata Kelola SPBE';
        } elseif ($indicatorId >= 17 && $indicatorId <= 24) {
            return 'Domain Manajemen SPBE';
        } else {
            return 'Domain Layanan SPBE';
        }
    }

}
