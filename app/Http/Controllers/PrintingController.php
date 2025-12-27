<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PrintJob;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PrintingController extends Controller
{
    public function index()
    {
        $userPrintJobs = Auth::check() 
            ? PrintJob::where('user_id', Auth::id())->latest()->take(5)->get()
            : collect();
            
        return view('printing.index', compact('userPrintJobs'));
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
                'paper_size' => 'required|in:A4,A3,Letter,Legal',
                'color_option' => 'required|in:black_white,color',
                'paper_type' => 'required|in:printing,photocopy',
                'copies' => 'required|integer|min:1|max:100',
                'orientation' => 'required|in:portrait,landscape',
                'notes' => 'nullable|string|max:500',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all())], 422);
        }

        if (!Auth::check()) {
            return response()->json(['error' => 'Please login to upload files for printing.'], 401);
        }

        try {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            
            // Generate unique filename
            $filename = Str::uuid() . '.' . $extension;
            
            // Test if private disk exists
            if (!config('filesystems.disks.private')) {
                return response()->json(['error' => 'Private disk configuration not found'], 500);
            }
            
            // Store file in print_jobs directory using local disk
            $filePath = $file->storeAs('print_jobs', $filename, 'local');
            
            if (!$filePath) {
                return response()->json(['error' => 'Failed to store file'], 500);
            }
            
            // Estimate page count (basic estimation)
            $pageCount = $this->estimatePageCount($file, $extension);
            
            // Create print job record
            $printJob = PrintJob::create([
                'user_id' => Auth::id(),
                'filename' => $filename,
                'original_filename' => $originalName,
                'file_path' => $filePath,
                'file_type' => $extension,
                'file_size' => $file->getSize(),
                'paper_size' => $request->paper_size,
                'color_option' => $request->color_option,
                'paper_type' => $request->paper_type,
                'copies' => $request->copies,
                'orientation' => $request->orientation,
                'page_count' => $pageCount,
                'notes' => $request->notes,
                'status' => 'uploaded'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully!',
                'print_job' => $printJob,
                'total_price' => $printJob->total_price
            ]);

        } catch (\Exception $e) {
            \Log::error('Print job upload error: ' . $e->getMessage());
            return response()->json(['error' => 'Upload failed: ' . $e->getMessage()], 500);
        }
    }

    public function addToCart(Request $request, PrintJob $printJob)
    {
        if ($printJob->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Create a virtual "print service" product
        $productName = "Print Job: {$printJob->original_filename}";
        $productDesc = "Print specifications: {$printJob->copies} copies, {$printJob->color_option_display}, {$printJob->paper_size} {$printJob->paper_type_display}";

        // Create temporary product for the print job
        $product = Product::create([
            'name' => $productName,
            'desc' => $productDesc,
            'category' => 'Printing Services',
            'type' => 'others',
            'price' => $printJob->total_price,
            'is_active' => true,
            'img' => 'images/UNISSA_CAFE.png' // Using cafe logo as placeholder
        ]);

        // Add to cart
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->update([
                'quantity' => 1, // Print jobs are unique, don't accumulate
                'total_price' => $printJob->total_price,
                'notes' => "Print Job ID: {$printJob->id}" . ($cartItem->notes ? '; ' . $cartItem->notes : '')
            ]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => 1,
                'unit_price' => $printJob->total_price,
                'total_price' => $printJob->total_price,
                'notes' => "Print Job ID: {$printJob->id}"
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Print job added to cart!',
            'redirect' => route('cart.index')
        ]);
    }

    public function downloadFile(PrintJob $printJob)
    {
        if ($printJob->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        if (!Storage::disk('local')->exists($printJob->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('local')->download(
            $printJob->file_path,
            $printJob->original_filename
        );
    }

    public function destroy(PrintJob $printJob)
    {
        if ($printJob->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete file from storage
        Storage::disk('local')->delete($printJob->file_path);
        
        // Delete record
        $printJob->delete();

        return response()->json(['success' => true, 'message' => 'Print job deleted successfully']);
    }

    private function estimatePageCount($file, $extension): int
    {
        // Basic page count estimation
        // For PDFs, you could use a PDF library to get exact count
        // For now, we'll estimate based on file size
        
        $fileSize = $file->getSize();
        
        return match(strtolower($extension)) {
            'pdf' => max(1, intval($fileSize / 50000)), // Rough estimate: 50KB per page
            'doc', 'docx' => max(1, intval($fileSize / 30000)), // 30KB per page
            'jpg', 'jpeg', 'png' => 1, // Images are typically 1 page
            default => 1
        };
    }
}
