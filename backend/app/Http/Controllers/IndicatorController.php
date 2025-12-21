<?php

namespace App\Http\Controllers;

use App\Models\Indicator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IndicatorController extends Controller
{
    /**
     * ====================================================================
     * PUBLIC API ENDPOINTS (For Frontend)
     * ====================================================================
     */

    /**
     * Get all active indicators in format compatible with app.js
     * 
     * GET /api/indicators
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndicators()
    {
        try {
            $indicators = Indicator::active()
                ->ordered()
                ->get();

            // Group indicators by group_name
            $grouped = $indicators->groupBy('group_name')->map(function ($items) {
                return $items->map(function ($indicator) {
                    return [
                        'id' => $indicator->id,
                        'name' => $indicator->indicator_text,
                        'group' => $indicator->group_name,
                        'type' => $indicator->type,
                        'scaleValues' => $indicator->scale_values,
                        'scaleLabels' => $indicator->scale_labels,
                    ];
                })->values();
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'indicators' => $grouped,
                    'version' => Indicator::getCurrentVersion(),
                    'last_updated' => Indicator::getLastUpdated(),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch indicators',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get current indicator version (for polling/caching)
     * 
     * GET /api/indicators/version
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVersion()
    {
        try {
            return response()->json([
                'success' => true,
                'version' => Indicator::getCurrentVersion(),
                'last_updated' => Indicator::getLastUpdated(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch version',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * ====================================================================
     * ADMIN CRUD ENDPOINTS
     * ====================================================================
     */

    /**
     * Get all indicators (including inactive) with pagination - ADMIN
     * 
     * GET /api/admin/indicators
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $search = $request->get('search', '');
            $status = $request->get('status', 'all'); // all, active, inactive

            $query = Indicator::query();

            // Apply search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('indicator_text', 'like', "%{$search}%")
                      ->orWhere('group_name', 'like', "%{$search}%");
                });
            }

            // Apply status filter
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }

            $indicators = $query->ordered()->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $indicators,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch indicators',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create new indicator - ADMIN
     * 
     * POST /api/admin/indicators
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_name' => 'required|string|max:255',
            'indicator_text' => 'required|string',
            'type' => 'required|in:scale,boolean,text',
            'scale_values' => 'nullable|array',
            'scale_labels' => 'nullable|array',
            'display_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $indicator = Indicator::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Indicator created successfully',
                'data' => $indicator,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create indicator',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get single indicator - ADMIN
     * 
     * GET /api/admin/indicators/{id}
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $indicator = Indicator::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $indicator,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Indicator not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Update indicator - ADMIN
     * 
     * PUT/PATCH /api/admin/indicators/{id}
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'group_name' => 'sometimes|required|string|max:255',
            'indicator_text' => 'sometimes|required|string',
            'type' => 'sometimes|required|in:scale,boolean,text',
            'scale_values' => 'nullable|array',
            'scale_labels' => 'nullable|array',
            'display_order' => 'sometimes|required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $indicator = Indicator::findOrFail($id);
            $indicator->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Indicator updated successfully',
                'data' => $indicator,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update indicator',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Soft delete (deactivate) indicator - ADMIN
     * 
     * DELETE /api/admin/indicators/{id}
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $indicator = Indicator::findOrFail($id);
            $indicator->update(['is_active' => false]);

            return response()->json([
                'success' => true,
                'message' => 'Indicator deactivated successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to deactivate indicator',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk update display order - ADMIN
     * 
     * POST /api/admin/indicators/reorder
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:indicators,id',
            'orders.*.display_order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            foreach ($request->orders as $order) {
                Indicator::where('id', $order['id'])
                    ->update(['display_order' => $order['display_order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Indicators reordered successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder indicators',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
