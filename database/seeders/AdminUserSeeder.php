<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@unissa.edu.bn'],
            [
                'name' => 'Admin',
                'password' => Hash::make('adminpassword'), // Change this to a secure password
                'role' => 'admin',
            ]
        );
    }
}
