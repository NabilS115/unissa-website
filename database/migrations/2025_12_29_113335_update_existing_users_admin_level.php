<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update admin_level based on existing role field
        DB::table('users')->where('role', 'admin')->update(['admin_level' => 'admin']);
        DB::table('users')->where('role', 'user')->orWhereNull('role')->update(['admin_level' => 'user']);
        
        // Set the first admin user as super_admin if no super_admin exists
        $firstAdmin = DB::table('users')->where('admin_level', 'admin')->orderBy('created_at')->first();
        if ($firstAdmin && !DB::table('users')->where('admin_level', 'super_admin')->exists()) {
            DB::table('users')->where('id', $firstAdmin->id)->update(['admin_level' => 'super_admin']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert admin_level back to role field values
        DB::table('users')->where('admin_level', 'super_admin')->update(['admin_level' => 'admin']);
        DB::table('users')->where('admin_level', 'moderator')->update(['admin_level' => 'user']);
    }
};
