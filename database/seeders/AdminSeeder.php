<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('P@ssw0rd'),
            'email_verified_at' => now(),
            'role' => 'admin',
        ]);
        
        // Memberikan role admin
        $admin->assignRole('admin');
        
        // Membuat user kasir
        $kasir = User::create([
            'name' => 'Kasir',
            'email' => 'kasir@admin.com',
            'password' => Hash::make('P@ssw0rd'),
            'email_verified_at' => now(),
            'role' => 'kasir',
        ]);
        
        // Memberikan role kasir
        $kasir->assignRole('kasir');
    }
}
