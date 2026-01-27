<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['phone' => 'admin'], // Login with 'admin' as phone number for simplicity
            [
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => null, // OTP login, or you can set a password if you implemented password login
                'role' => 'admin',
                'status' => 'active',
                'otp_code' => null,
                'email_verified_at' => now(),
            ]
        );
    }
}
