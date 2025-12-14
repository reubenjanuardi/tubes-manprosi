<?php

namespace App\Http\Controllers;

use App\Models\AssessmentProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ProgressController extends Controller
{
    /**
     * Store or update assessment progress
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'assessment_id' => 'nullable|uuid',
                'progress_data' => 'required|array',
            ]);

            // Find existing progress or create new one
            $progress = AssessmentProgress::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'assessment_id' => $validated['assessment_id'] ?? null,
                ],
                [
                    'progress_data' => $validated['progress_data'],
                    'saved_at' => now(),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Progress saved successfully',
                'data' => [
                    'progress_id' => $progress->id,
                    'saved_at' => $progress->saved_at,
                ],
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save progress',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user's assessment progress
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $progress = AssessmentProgress::where('user_id', Auth::id())
                ->latest('saved_at')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $progress,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve progress',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get specific progress by ID
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        try {
            $progress = AssessmentProgress::where('user_id', Auth::id())
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $progress,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Progress not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Delete progress
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            $progress = AssessmentProgress::where('user_id', Auth::id())
                ->findOrFail($id);
            
            $progress->delete();

            return response()->json([
                'success' => true,
                'message' => 'Progress deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete progress',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
