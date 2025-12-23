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

    public function contact()
    {
        // Only allow admin access
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('admin.content.editcontact');
    }

    public function about()
    {
        // Only allow admin access
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('admin.content.editabout');
    }

    public function privacy()
    {
        // Only allow admin access
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('admin.content.editprivacy');
    }

    public function terms()
    {
        // Only allow admin access
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('admin.content.editterms');
    }

    public function unissaCafeHomepage()
    {
        // Only allow admin access
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('admin.content.editunissacafe');
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
                // Determine content type based on field
                $contentType = (str_contains($key, 'address') || str_contains($key, 'hours')) ? 'html' : 'html';
                ContentBlock::set($key, $content, $contentType, 'homepage');
            }
        }

        return response()->json(['success' => true, 'message' => 'Homepage content updated successfully!']);
    }

    public function updateContact(Request $request)
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
                ContentBlock::set($key, $content, 'html', 'contact');
            }
        }

        return response()->json(['success' => true, 'message' => 'Contact page content updated successfully!']);
    }

    public function updateAbout(Request $request)
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
            // Skip removal markers - they're handled by checking empty image content
            if (str_contains($key, '_removed')) {
                continue;
            }
            
            // Handle all content - save with appropriate type for rendering
            $contentType = (str_contains($key, 'subtitle') || str_contains($key, 'address')) ? 'html' : 'html';
            ContentBlock::set($key, $content, $contentType, 'about');
        }

        return response()->json(['success' => true, 'message' => 'About page content updated successfully!']);
    }

    public function updatePrivacy(Request $request)
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
                ContentBlock::set($key, $content, 'html', 'privacy');
            }
        }

        return response()->json(['success' => true, 'message' => 'Privacy policy updated successfully!']);
    }

    public function updateTerms(Request $request)
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
                ContentBlock::set($key, $content, 'html', 'terms');
            }
        }

        return response()->json(['success' => true, 'message' => 'Terms of service updated successfully!']);
    }

    public function updateUnissaCafeHomepage(Request $request)
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
                ContentBlock::set($key, $content, 'html', 'unissa-cafe');
            }
        }

        return response()->json(['success' => true, 'message' => 'Unissa Cafe homepage content updated successfully!']);
    }

    public function updateBankTransferSettings(Request $request)
    {
        // Only allow admin access
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $validated = $request->validate([
                'content.bank_account_name' => 'nullable|string|max:255',
                'content.bank_account_number' => 'nullable|string|max:50',
            ]);

            foreach ($validated['content'] as $key => $content) {
                if (!empty(trim($content))) {
                    ContentBlock::set($key, $content, 'text', 'bank-transfer');
                }
            }

            return response()->json(['success' => true, 'message' => 'Bank transfer settings updated successfully!']);
        } catch (\Exception $e) {
            \Log::error('Bank transfer settings update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error updating settings: ' . $e->getMessage()], 500);
        }
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

    public function footer()
    {
        // Only allow admin access
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('admin.content.editfooter');
    }

    public function updateFooter(Request $request)
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
            // Determine page context from the key
            $page = str_contains($key, 'tijarah') ? 'tijarah-footer' : 'unissa-footer';
            
            // Clean content - allow empty strings for removal of optional fields like social media
            $content = trim($content ?? '');
            
            ContentBlock::set($key, $content, 'text', $page);
        }

        return response()->json(['success' => true, 'message' => 'Footer content updated successfully!']);
    }
}
