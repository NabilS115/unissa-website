<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $galleries = Gallery::ordered()->get()->map(function ($gallery) {
            return [
                'id' => $gallery->id,
                'image_url' => $gallery->image_url,
                'image_path' => $gallery->image_path,
                'is_active' => $gallery->is_active,
                'sort_order' => $gallery->sort_order,
                'created_at' => $gallery->created_at,
                'updated_at' => $gallery->updated_at,
            ];
        });

        return response()->json($galleries);
    }

    public function store(Request $request)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0'
        ]);

        $validated['is_active'] = $request->has('is_active') && $request->is_active == '1';
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        try {
            // Store the uploaded file
            $imagePath = $request->file('image')->store('gallery', 'public');

            $gallery = Gallery::create([
                'image_path' => $imagePath,
                'is_active' => $validated['is_active'],
                'sort_order' => $validated['sort_order']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Gallery image added successfully',
                'gallery' => [
                    'id' => $gallery->id,
                    'image_url' => $gallery->image_url,
                    'image_path' => $gallery->image_path,
                    'is_active' => $gallery->is_active,
                    'sort_order' => $gallery->sort_order,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add gallery image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Gallery $gallery)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0'
        ]);

        try {
            $updateData = [];

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $oldImagePath = $gallery->image_path;
                
                // Delete old image if it exists and is a file path (not URL)
                if ($oldImagePath && !filter_var($oldImagePath, FILTER_VALIDATE_URL) && Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }

                // Store new image
                $updateData['image_path'] = $request->file('image')->store('gallery', 'public');
            }

            // Update other fields
            if ($request->has('is_active')) {
                $updateData['is_active'] = $request->is_active == '1';
            }
            
            if (isset($validated['sort_order'])) {
                $updateData['sort_order'] = $validated['sort_order'];
            }

            $gallery->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Gallery image updated successfully',
                'gallery' => [
                    'id' => $gallery->id,
                    'image_url' => $gallery->image_url,
                    'image_path' => $gallery->image_path,
                    'is_active' => $gallery->is_active,
                    'sort_order' => $gallery->sort_order,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update gallery image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Gallery $gallery)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $gallery->delete(); // The model's boot method will handle file deletion

            return response()->json([
                'success' => true,
                'message' => 'Gallery image deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete gallery image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleActive(Gallery $gallery)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $gallery->update(['is_active' => !$gallery->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'Gallery image status updated successfully',
                'gallery' => [
                    'id' => $gallery->id,
                    'image_url' => $gallery->image_url,
                    'image_path' => $gallery->image_path,
                    'is_active' => $gallery->is_active,
                    'sort_order' => $gallery->sort_order,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update gallery image status: ' . $e->getMessage()
            ], 500);
        }
    }
}
