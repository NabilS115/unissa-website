<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VendorController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $vendors = Vendor::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
            
            $vendorsData = $vendors->map(function ($vendor) {
                return [
                    'id' => $vendor->id,
                    'name' => $vendor->name,
                    'type' => $vendor->type,
                    'description' => $vendor->description,
                    'image_url' => $vendor->getImageUrlAttribute(),
                    'is_active' => $vendor->is_active,
                    'sort_order' => $vendor->sort_order,
                    'created_at' => $vendor->created_at->format('Y-m-d H:i:s')
                ];
            });

            return response()->json($vendorsData);
        } catch (\Exception $e) {
            \Log::error('Vendor index error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load vendors'], 500);
        }
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        try {
            // If no sort_order provided, assign the next available order
            $sortOrder = $request->sort_order;
            if ($sortOrder === null || $sortOrder === '') {
                $maxOrder = Vendor::max('sort_order') ?? -1;
                $sortOrder = $maxOrder + 1;
            } else {
                $this->adjustSortOrders($sortOrder);
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = Str::random(40) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('vendors', $filename, 'public');
                $imagePath = 'vendors/' . $filename;
            }
            
            Vendor::create([
                'name' => $request->name,
                'type' => $request->type,
                'description' => $request->description,
                'image_path' => $imagePath,
                'sort_order' => $sortOrder,
                'is_active' => $request->boolean('is_active', true)
            ]);

            return response()->json(['message' => 'Vendor created successfully!']);
        } catch (\Exception $e) {
            \Log::error('Vendor store error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to create vendor.'], 500);
        }
    }

    public function update(Request $request, Vendor $vendor)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        try {
            $oldSortOrder = $vendor->sort_order;
            $newSortOrder = $request->sort_order ?? $oldSortOrder;

            // If sort order is changing, adjust other vendors
            if ($oldSortOrder !== $newSortOrder) {
                $this->adjustSortOrders($newSortOrder, $vendor->id);
            }

            // Handle image update if provided
            if ($request->hasFile('image')) {
                // Delete old image
                if ($vendor->image_path && Storage::disk('public')->exists($vendor->image_path)) {
                    Storage::disk('public')->delete($vendor->image_path);
                }
                
                // Store new image
                $image = $request->file('image');
                $filename = Str::random(40) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('vendors', $filename, 'public');
                
                $vendor->image_path = 'vendors/' . $filename;
            }

            $vendor->name = $request->name;
            $vendor->type = $request->type;
            $vendor->description = $request->description;
            $vendor->sort_order = $newSortOrder;
            $vendor->is_active = $request->boolean('is_active', $vendor->is_active);
            $vendor->save();

            return response()->json(['message' => 'Vendor updated successfully!']);
        } catch (\Exception $e) {
            \Log::error('Vendor update error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update vendor.'], 500);
        }
    }

    public function destroy(Vendor $vendor)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            // Delete the image file
            if ($vendor->image_path && Storage::disk('public')->exists($vendor->image_path)) {
                Storage::disk('public')->delete($vendor->image_path);
            }

            // Delete the vendor record
            $vendor->delete();

            return response()->json(['message' => 'Vendor deleted successfully!']);
        } catch (\Exception $e) {
            \Log::error('Vendor destroy error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to delete vendor.'], 500);
        }
    }

    public function toggleActive(Vendor $vendor)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $vendor->is_active = !$vendor->is_active;
            $vendor->save();

            return response()->json([
                'message' => 'Vendor status updated successfully!',
                'is_active' => $vendor->is_active
            ]);
        } catch (\Exception $e) {
            \Log::error('Vendor toggle error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update vendor status.'], 500);
        }
    }

    private function adjustSortOrders($newSortOrder, $excludeId = null)
    {
        // Shift vendors with sort_order >= newSortOrder by +1
        $query = Vendor::where('sort_order', '>=', $newSortOrder);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        $vendorsToShift = $query->get();
        
        foreach ($vendorsToShift as $vendor) {
            $vendor->increment('sort_order');
        }
    }
}
