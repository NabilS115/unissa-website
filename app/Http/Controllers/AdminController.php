<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Review;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function profile()
    {
        // Ensure only admins can access this
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        // Get platform statistics
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_reviews' => Review::count(),
            'total_vendors' => Vendor::count(),
            'pending_reports' => 0, // You can add a reports table later
            'resolved_reports' => 0, // You can add a reports table later
        ];

        // Get recent activity (last 24 hours)
        $recentActivity = collect([
            // New user registrations
            ...User::where('created_at', '>=', Carbon::now()->subDay())
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($user) {
                    return [
                        'type' => 'user_registration',
                        'title' => 'New User Registration',
                        'description' => "@{$user->name} registered as a new user",
                        'time' => $user->created_at->diffForHumans(),
                        'icon' => 'user',
                        'color' => 'blue'
                    ];
                }),
            
            // New reviews
            ...Review::with(['user', 'product'])
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($review) {
                    return [
                        'type' => 'new_review',
                        'title' => 'New Review Posted',
                        'description' => "{$review->user->name} reviewed {$review->product->name} ({$review->rating} stars)",
                        'time' => $review->created_at->diffForHumans(),
                        'icon' => 'star',
                        'color' => 'yellow'
                    ];
                }),
            
            // New products
            ...Product::where('created_at', '>=', Carbon::now()->subDay())
                ->latest()
                ->take(3)
                ->get()
                ->map(function ($product) {
                    return [
                        'type' => 'new_product',
                        'title' => 'New Product Added',
                        'description' => "Product '{$product->name}' was added to {$product->category}",
                        'time' => $product->created_at->diffForHumans(),
                        'icon' => 'package',
                        'color' => 'green'
                    ];
                })
        ])->sortByDesc(function ($activity) {
            return Carbon::parse($activity['time']);
        })->take(10)->values();

        // Get growth metrics (compare with previous month)
        $currentMonth = Carbon::now()->startOfMonth();
        $previousMonth = Carbon::now()->subMonth()->startOfMonth();
        
        $currentMonthUsers = User::where('created_at', '>=', $currentMonth)->count();
        $previousMonthUsers = User::whereBetween('created_at', [$previousMonth, $currentMonth])->count();
        
        $userGrowth = $previousMonthUsers > 0 
            ? round((($currentMonthUsers - $previousMonthUsers) / $previousMonthUsers) * 100, 1)
            : 0;

        $activeUsers = User::where('updated_at', '>=', Carbon::now()->subDays(30))->count();

        // Get top-rated products
        $topProducts = Product::withCount('reviews')
            ->with('reviews')
            ->having('reviews_count', '>', 0)
            ->get()
            ->map(function ($product) {
                $averageRating = $product->reviews->avg('rating');
                return [
                    'name' => $product->name,
                    'category' => $product->category,
                    'rating' => round($averageRating, 1),
                    'review_count' => $product->reviews_count
                ];
            })
            ->sortByDesc('rating')
            ->take(5)
            ->values();

        return view('admin-profile', compact('stats', 'recentActivity', 'userGrowth', 'activeUsers', 'topProducts'));
    }

    public function manageUsers()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        $users = User::with('reviews')->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function deleteUser(User $user)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Don't allow deletion of other admins
        if ($user->role === 'admin') {
            return response()->json(['message' => 'Cannot delete admin users'], 403);
        }

        try {
            // Delete user's reviews first
            $user->reviews()->delete();
            
            // Delete the user
            $user->delete();
            
            return response()->json(['message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete user'], 500);
        }
    }

    public function toggleUserStatus(User $user)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            // Toggle user active status (you might need to add this column to users table)
            $user->is_active = !($user->is_active ?? true);
            $user->save();
            
            return response()->json([
                'message' => 'User status updated successfully',
                'is_active' => $user->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update user status'], 500);
        }
    }
}
