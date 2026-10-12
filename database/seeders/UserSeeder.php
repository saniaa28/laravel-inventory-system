<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Password di-hash otomatis oleh cast 'hashed' di model User
        User::create([
            'name' => 'INVORA Administrator', 'username' => 'admin',
            'email' => 'admin@invora.test', 'password' => 'admin123', 'role' => 'admin',
        ]);

        User::create([
            'name' => 'INVORA Staff', 'username' => 'staff',
            'email' => 'staff@invora.test', 'password' => 'staff123', 'role' => 'staff',
        ]);
    }
}
