<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = config('admin.email');

        if (!User::where('email', $email)->exists()) {
            User::create([
                'name' => 'Admin Portfolio',
                'email' => $email,
                'password' => Hash::make('password'),
            ]);
        }
    }
}
