<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UserController extends Controller
{
    // Remove middleware from constructor since we handle it in routes
    public function __construct()
    {
        // Constructor can be empty or handle other initialization
    }

    public function index()
    {
        // Add check here as backup
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        $stats = $this->getUserStats();
        
        return view('admin.users', [
            'totalUsers' => $stats['total'],
            'activeUsers' => $stats['active'],
            'adminUsers' => $stats['admins'],
            'newUsers' => $stats['new_this_month']
        ]);
    }

    public function api(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $page = $request->get('page', 1);
        $search = trim($request->get('search', ''));
        $roleFilter = $request->get('role', '');
        $statusFilter = $request->get('status', '');

        $query = User::withCount('reviews');

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Apply role filter
        if ($roleFilter && in_array($roleFilter, ['user', 'admin'])) {
            $query->where('role', $roleFilter);
        }

        // Apply status filter
        if ($statusFilter) {
            if ($statusFilter === 'active') {
                $query->where('is_active', true);
            } elseif ($statusFilter === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        $pagination = [
            'current_page' => $users->currentPage(),
            'total_pages' => $users->lastPage(),
            'total' => $users->total(),
            'from' => $users->firstItem() ?? 0,
            'to' => $users->lastItem() ?? 0,
            'per_page' => $users->perPage()
        ];

        $stats = $this->getUserStats();

        // Format user data
        $userData = $users->items();
        foreach ($userData as $user) {
            $user->profile_photo_url = $user->profile_photo_url ?? 
                'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=14b8a6&color=fff';
            $user->is_active = (bool) $user->is_active;
        }

        return response()->json([
            'users' => $userData,
            'pagination' => $pagination,
            'stats' => $stats
        ]);
    }

    public function show(User $user)
    {
        $user->load('reviews');
        $user->profile_photo_url = $user->profile_photo_url ?? 
            'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=14b8a6&color=fff';
        
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'role' => 'required|in:user,admin',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'is_active' => $request->boolean('is_active', true),
                'email_verified_at' => now()
            ]);

            \Log::info('User created by admin', [
                'admin_id' => auth()->id(),
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

            return response()->json([
                'message' => 'User created successfully!',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create user', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);
            
            return response()->json([
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|string|min:8',
            'role' => 'required|in:user,admin',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Prevent admin from demoting themselves
        if ($user->id === auth()->id() && $request->role !== 'admin') {
            return response()->json([
                'message' => 'You cannot change your own role.'
            ], 403);
        }

        // Prevent admin from deactivating themselves
        if ($user->id === auth()->id() && !$request->boolean('is_active', true)) {
            return response()->json([
                'message' => 'You cannot deactivate your own account.'
            ], 403);
        }

        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'is_active' => $request->boolean('is_active', true)
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            \Log::info('User updated by admin', [
                'admin_id' => auth()->id(),
                'user_id' => $user->id,
                'changes' => array_keys($data)
            ]);

            return response()->json([
                'message' => 'User updated successfully!',
                'user' => $user->fresh()
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to update user', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id(),
                'user_id' => $user->id
            ]);
            
            return response()->json([
                'message' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        // Prevent deleting the current admin user
        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'You cannot delete your own account.'
            ], 403);
        }

        try {
            // Store user info for logging
            $userInfo = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ];

            // Delete user's reviews first (soft delete if you have that set up)
            Review::where('user_id', $user->id)->delete();
            
            // Delete the user
            $user->delete();

            \Log::info('User deleted by admin', [
                'admin_id' => auth()->id(),
                'deleted_user' => $userInfo
            ]);

            return response()->json([
                'message' => 'User deleted successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to delete user', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id(),
                'user_id' => $user->id
            ]);
            
            return response()->json([
                'message' => 'Failed to delete user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus(User $user)
    {
        // Prevent deactivating the current admin user
        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'You cannot change your own account status.'
            ], 403);
        }

        try {
            $oldStatus = $user->is_active;
            $user->update([
                'is_active' => !$user->is_active
            ]);

            $status = $user->is_active ? 'activated' : 'deactivated';
            
            \Log::info('User status changed by admin', [
                'admin_id' => auth()->id(),
                'user_id' => $user->id,
                'old_status' => $oldStatus,
                'new_status' => $user->is_active
            ]);
            
            return response()->json([
                'message' => "User {$status} successfully!",
                'is_active' => $user->is_active
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to update user status', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id(),
                'user_id' => $user->id
            ]);
            
            return response()->json([
                'message' => 'Failed to update user status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export(Request $request)
    {
        $search = trim($request->get('search', ''));
        $roleFilter = $request->get('role', '');
        $statusFilter = $request->get('status', '');

        $query = User::withCount('reviews');

        // Apply filters (same as API method)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($roleFilter && in_array($roleFilter, ['user', 'admin'])) {
            $query->where('role', $roleFilter);
        }

        if ($statusFilter) {
            if ($statusFilter === 'active') {
                $query->where('is_active', true);
            } elseif ($statusFilter === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        // Generate CSV content
        $csvContent = "Name,Email,Role,Status,Reviews Count,Joined Date,Last Updated\n";
        
        foreach ($users as $user) {
            $csvContent .= sprintf(
                '"%s","%s","%s","%s","%s","%s","%s"' . "\n",
                str_replace('"', '""', $user->name),
                str_replace('"', '""', $user->email),
                ucfirst($user->role ?? 'user'),
                $user->is_active ? 'Active' : 'Inactive',
                $user->reviews_count ?? 0,
                $user->created_at->format('Y-m-d H:i:s'),
                $user->updated_at->format('Y-m-d H:i:s')
            );
        }

        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';

        \Log::info('Users exported by admin', [
            'admin_id' => auth()->id(),
            'export_count' => $users->count(),
            'filters' => compact('search', 'roleFilter', 'statusFilter')
        ]);

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    private function getUserStats()
    {
        try {
            $total = User::count();
            $active = User::where('is_active', true)->count();
            $admins = User::where('role', 'admin')->count();
            $newThisMonth = User::where('created_at', '>=', Carbon::now()->startOfMonth())->count();

            return [
                'total' => $total,
                'active' => $active,
                'admins' => $admins,
                'new_this_month' => $newThisMonth
            ];
        } catch (\Exception $e) {
            \Log::error('Failed to get user stats', ['error' => $e->getMessage()]);
            
            return [
                'total' => 0,
                'active' => 0,
                'admins' => 0,
                'new_this_month' => 0
            ];
        }
    }
}
