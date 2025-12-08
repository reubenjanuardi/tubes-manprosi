<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentResponse;
use App\Helpers\IndicatorMapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

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
        try {
            $assessment = Assessment::with('responses')->findOrFail($id);

            // Prepare data for PDF view
            $data = [
                'assessment' => $assessment,
                'indicators' => IndicatorMapper::getIndicators(),
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
     * Export assessment as Excel
     */
    public function exportExcel(string $id)
    {
        try {
            $assessment = Assessment::with('responses')->findOrFail($id);

            // Create Excel export object
            $export = new \App\Exports\AssessmentExport($assessment);
            $data = $export->getExcelData();

            // Generate Excel using a simple approach with PHPExcel
            $filename = "Assessment_{$assessment->org_name}_{$assessment->id}.xlsx";

            // Return data as JSON for frontend to handle
            return response()->json([
                'success' => true,
                'data' => $data,
                'filename' => $filename,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export Excel',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
