<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContentBlock;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    public function homepage()
    {
        // Only allow admin access
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('admin.content.edithomepage');
    }

    public function updateHomepage(Request $request)
    {
        // Only allow admin access
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'content' => 'required|array',
            'content.*' => 'nullable|string'
        ]);

        foreach ($validated['content'] as $key => $content) {
            // Only update if content is not empty
            if (!empty(trim($content))) {
                ContentBlock::set($key, $content, 'html', 'homepage');
            }
        }

        return response()->json(['success' => true, 'message' => 'Homepage content updated successfully!']);
    }

    public function uploadImage(Request $request)
    {
        // Only allow admin access
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'image' => 'required|image|max:5120', // 5MB max
        ]);

        $path = $request->file('image')->store('content', 'public');
        $url = '/storage/' . $path;

        return response()->json(['success' => true, 'url' => $url]);
    }
}
