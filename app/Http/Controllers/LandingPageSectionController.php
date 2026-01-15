<?php

namespace App\Http\Controllers;

use App\Models\LandingPageSection;
use App\Models\ProductCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LandingPageSectionController extends Controller
{
    /**
     * Display a listing of sections
     */
    public function index(Request $request)
    {
        $sections = LandingPageSection::orderBy('display_order')
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'sections' => $sections,
        ]);
    }

    /**
     * Get active sections for landing page
     */
    public function active()
    {
        $sections = LandingPageSection::where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'sections' => $sections,
        ]);
    }

    /**
     * Store a newly created section
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:landing_page_sections,title',
            'source_type' => 'required|in:collection',
            'source_value' => 'required|string|max:255',
            'product_count' => 'nullable|integer|min:1|max:50',
            'display_style' => 'nullable|in:grid,slider',
            'is_active' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Validate source_value - must be a valid collection
        $collection = ProductCollection::where('slug', $request->source_value)->first();
        if (!$collection) {
            return response()->json([
                'success' => false,
                'errors' => ['source_value' => ['The selected collection does not exist.']],
            ], 422);
        }

        $section = LandingPageSection::create($request->all());

        return response()->json([
            'success' => true,
            'section' => $section,
        ], 201);
    }

    /**
     * Display the specified section
     */
    public function show(LandingPageSection $landingPageSection)
    {
        return response()->json([
            'success' => true,
            'section' => $landingPageSection,
        ]);
    }

    /**
     * Update the specified section
     */
    public function update(Request $request, LandingPageSection $landingPageSection)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255|unique:landing_page_sections,title,' . $landingPageSection->id,
            'source_type' => 'sometimes|required|in:collection',
            'source_value' => 'sometimes|required|string|max:255',
            'product_count' => 'nullable|integer|min:1|max:50',
            'display_style' => 'nullable|in:grid,slider',
            'is_active' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Validate source_value if provided - must be a valid collection
        if ($request->has('source_value')) {
            $sourceValue = $request->source_value ?? $landingPageSection->source_value;
            $collection = ProductCollection::where('slug', $sourceValue)->first();
            if (!$collection) {
                return response()->json([
                    'success' => false,
                    'errors' => ['source_value' => ['The selected collection does not exist.']],
                ], 422);
            }
        }

        $landingPageSection->update($request->all());

        return response()->json([
            'success' => true,
            'section' => $landingPageSection->fresh(),
        ]);
    }

    /**
     * Remove the specified section
     */
    public function destroy(LandingPageSection $landingPageSection)
    {
        $landingPageSection->delete();

        return response()->json([
            'success' => true,
            'message' => 'Section deleted successfully',
        ]);
    }

    /**
     * Update section order
     */
    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:landing_page_sections,id',
            'sections.*.display_order' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        foreach ($request->sections as $item) {
            LandingPageSection::where('id', $item['id'])
                ->update(['display_order' => $item['display_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Section order updated successfully',
        ]);
    }
}
