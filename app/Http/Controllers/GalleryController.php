<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $galleries = Gallery::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
            
            $galleriesData = $galleries->map(function ($gallery) {
                return [
                    'id' => $gallery->id,
                    'image_url' => $gallery->getImageUrlAttribute(),
                    'is_active' => $gallery->is_active,
                    'sort_order' => $gallery->sort_order,
                    'created_at' => $gallery->created_at->format('Y-m-d H:i:s')
                ];
            });

            return response()->json($galleriesData);
        } catch (\Exception $e) {
            \Log::error('Gallery index error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load images'], 500);
        }
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        try {
            // If no sort_order provided, assign the next available order
            $sortOrder = $request->sort_order;
            if ($sortOrder === null || $sortOrder === '') {
                // Get the highest current sort_order and add 1
                $maxOrder = Gallery::max('sort_order') ?? -1;
                $sortOrder = $maxOrder + 1;
            } else {
                // If sort_order is provided, shift other images if needed
                $this->adjustSortOrders($sortOrder);
            }

            $image = $request->file('image');
            $filename = Str::random(40) . '.' . $image->getClientOriginalExtension();
            
            $image->storeAs('gallery', $filename, 'public');
            
            Gallery::create([
                'image_path' => 'gallery/' . $filename,
                'sort_order' => $sortOrder,
                'is_active' => $request->boolean('is_active', true)
            ]);

            return response()->json(['message' => 'Image uploaded successfully!']);
        } catch (\Exception $e) {
            \Log::error('Gallery store error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to upload image.'], 500);
        }
    }

    public function update(Request $request, Gallery $gallery)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        try {
            $oldSortOrder = $gallery->sort_order;
            $newSortOrder = $request->sort_order ?? $oldSortOrder;

            // If sort order is changing, adjust other images
            if ($oldSortOrder !== $newSortOrder) {
                $this->adjustSortOrders($newSortOrder, $gallery->id);
            }

            // Handle image update if provided
            if ($request->hasFile('image')) {
                // Delete old image
                if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
                    Storage::disk('public')->delete($gallery->image_path);
                }
                
                // Store new image
                $image = $request->file('image');
                $filename = Str::random(40) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('gallery', $filename, 'public');
                
                $gallery->image_path = 'gallery/' . $filename;
            }

            $gallery->sort_order = $newSortOrder;
            $gallery->is_active = $request->boolean('is_active', $gallery->is_active);
            $gallery->save();

            return response()->json(['message' => 'Image updated successfully!']);
        } catch (\Exception $e) {
            \Log::error('Gallery update error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update image.'], 500);
        }
    }

    public function destroy(Gallery $gallery)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            // Delete the image file
            if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
                Storage::disk('public')->delete($gallery->image_path);
            }

            // Delete the gallery record
            $gallery->delete();

            return response()->json(['message' => 'Image deleted successfully!']);
        } catch (\Exception $e) {
            \Log::error('Gallery destroy error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to delete image.'], 500);
        }
    }

    public function toggleActive(Gallery $gallery)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $gallery->is_active = !$gallery->is_active;
            $gallery->save();

            return response()->json([
                'message' => 'Gallery status updated successfully!',
                'is_active' => $gallery->is_active
            ]);
        } catch (\Exception $e) {
            \Log::error('Gallery toggle error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update gallery status.'], 500);
        }
    }

    private function adjustSortOrders($newSortOrder, $excludeId = null)
    {
        // Shift images with sort_order >= newSortOrder by +1
        $query = Gallery::where('sort_order', '>=', $newSortOrder);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        $imagesToShift = $query->get();
        
        foreach ($imagesToShift as $image) {
            $image->increment('sort_order');
        }
    }
}
