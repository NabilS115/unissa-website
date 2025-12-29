<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Review;
use App\Models\AdminAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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
        try {
            $users = User::take(5)->get(['id', 'name', 'email', 'admin_level', 'is_active', 'created_at', 'profile_photo_url']);
            
            // Add profile photos
            foreach($users as $user) {
                $user->profile_photo_url = $user->profile_photo_url ?? 
                    'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=14b8a6&color=fff';
                $user->is_active = (bool) $user->is_active;
            }
            
            return response()->json([
                'users' => $users,
                'pagination' => [
                    'current_page' => 1,
                    'total_pages' => 1,
                    'total' => $users->count(),
                    'from' => 1,
                    'to' => $users->count(),
                    'per_page' => 5
                ],
                'stats' => [
                    'total' => User::count(),
                    'active' => User::where('is_active', true)->count(),
                    'admins' => User::whereIn('admin_level', ['admin', 'super_admin'])->count(),
                    'new_this_month' => 0
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('User API Error', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
            'admin_level' => 'required|in:user,moderator,admin,super_admin',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Generate temporary password
            $temporaryPassword = Str::random(12);
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($temporaryPassword),
                'admin_level' => $request->admin_level,
                'is_active' => $request->boolean('is_active', true),
                'email_verified_at' => null // User needs to verify email
            ]);

            // Send welcome email with password reset link
            // TODO: Implement welcome email with password reset

            // Log the action
            AdminAuditLog::logAction('create', $user, [], $user->only(['name', 'email', 'admin_level', 'is_active']), 'User created by admin');

            \Log::info('User created by admin', [
                'admin_id' => auth()->id(),
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

            return response()->json([
                'message' => 'User created successfully! They will receive an email to set their password.',
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
            'admin_level' => 'required|in:user,moderator,admin,super_admin',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Prevent admin from demoting themselves
        if ($user->id === auth()->id() && !in_array($request->admin_level, ['admin', 'super_admin'])) {
            return response()->json([
                'message' => 'You cannot demote yourself from admin privileges.'
            ], 403);
        }
        
        // Prevent admin from lowering their own admin level
        if ($user->id === auth()->id() && !auth()->user()->canManageAdminLevels() && $request->admin_level !== $user->admin_level) {
            return response()->json([
                'message' => 'You cannot change your own admin level.'
            ], 403);
        }
        
        // Check if current admin can manage the requested admin level
        if (!auth()->user()->canManageAdminLevels() && in_array($request->admin_level, ['admin', 'super_admin'])) {
            return response()->json([
                'message' => 'You do not have permission to assign this admin level.'
            ], 403);
        }

        // Prevent admin from deactivating themselves
        if ($user->id === auth()->id() && !$request->boolean('is_active', true)) {
            return response()->json([
                'message' => 'You cannot deactivate your own account.'
            ], 403);
        }

        try {
            // Capture old values for audit log
            $oldValues = $user->only(['name', 'email', 'admin_level', 'is_active']);
            
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'admin_level' => $request->admin_level,
                'is_active' => $request->boolean('is_active', true)
            ];

            $user->update($data);
            
            // Log the action
            AdminAuditLog::logAction('update', $user, $oldValues, $data, 'User updated by admin');

            \Log::info('User updated by admin', [
                'admin_id' => auth()->id(),
                'user_id' => $user->id,
                'changes' => array_keys($data)
            ]);

            return response()->json([
                'message' => 'User updated successfully! Password changes must be done by the user.',
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
                'email' => $user->email,
                'role' => $user->role
            ];
            
            // Log the action before deletion
            AdminAuditLog::logAction('soft_delete', $user, $user->only(['name', 'email', 'role', 'is_active']), [], 'User soft deleted by admin');

            // Soft delete the user (keeps reviews intact)
            $user->delete();

            \Log::info('User soft deleted by admin', [
                'admin_id' => auth()->id(),
                'deleted_user' => $userInfo
            ]);

            return response()->json([
                'message' => 'User deleted successfully! (Can be restored if needed)'
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
            
            // Log the action
            AdminAuditLog::logAction($user->is_active ? 'activate' : 'deactivate', $user, 
                ['is_active' => $oldStatus], 
                ['is_active' => $user->is_active], 
                "User {$status} by admin");
            
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
        $adminLevelFilter = $request->get('admin_level', '');
        $statusFilter = $request->get('status', '');

        $query = User::withCount('reviews');

        // Apply filters (same as API method)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($adminLevelFilter && in_array($adminLevelFilter, ['user', 'moderator', 'admin', 'super_admin'])) {
            $query->where('admin_level', $adminLevelFilter);
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
        $csvContent = "Name,Email,Admin Level,Status,Reviews Count,Joined Date,Last Updated\n";
        
        foreach ($users as $user) {
            $csvContent .= sprintf(
                '"%s","%s","%s","%s","%s","%s","%s"' . "\n",
                str_replace('"', '""', $user->name),
                str_replace('"', '""', $user->email),
                ucfirst(str_replace('_', ' ', $user->admin_level ?? 'user')),
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
            'filters' => compact('search', 'adminLevelFilter', 'statusFilter')
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
            $admins = User::whereIn('admin_level', ['admin', 'super_admin'])->count();
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

    public function restore($userId)
    {
        try {
            $user = User::withTrashed()->findOrFail($userId);
            
            if (!$user->trashed()) {
                return response()->json([
                    'message' => 'User is not deleted.'
                ], 400);
            }
            
            $user->restore();
            
            // Log the action
            AdminAuditLog::logAction('restore', $user, [], $user->only(['name', 'email', 'role', 'is_active']), 'User restored by admin');
            
            \Log::info('User restored by admin', [
                'admin_id' => auth()->id(),
                'user_id' => $user->id
            ]);
            
            return response()->json([
                'message' => 'User restored successfully!',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to restore user', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id(),
                'user_id' => $userId
            ]);
            
            return response()->json([
                'message' => 'Failed to restore user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function forceDelete($userId)
    {
        try {
            $user = User::withTrashed()->findOrFail($userId);
            
            // Only super admins can permanently delete
            if (auth()->user()->role !== 'super_admin') {
                return response()->json([
                    'message' => 'Only super administrators can permanently delete users.'
                ], 403);
            }
            
            // Prevent deleting the current admin user
            if ($user->id === auth()->id()) {
                return response()->json([
                    'message' => 'You cannot permanently delete your own account.'
                ], 403);
            }
            
            $userInfo = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ];
            
            // Log the action before permanent deletion
            AdminAuditLog::logAction('force_delete', $user, $user->only(['name', 'email', 'role', 'is_active']), [], 'User permanently deleted by super admin');
            
            // Delete user's reviews permanently
            Review::where('user_id', $user->id)->forceDelete();
            
            // Permanently delete the user
            $user->forceDelete();
            
            \Log::warning('User permanently deleted by super admin', [
                'admin_id' => auth()->id(),
                'deleted_user' => $userInfo
            ]);
            
            return response()->json([
                'message' => 'User permanently deleted!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to permanently delete user', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id(),
                'user_id' => $userId
            ]);
            
            return response()->json([
                'message' => 'Failed to permanently delete user: ' . $e->getMessage()
            ], 500);
        }
    }
}
