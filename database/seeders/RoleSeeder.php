<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Membuat role
        $adminRole = Role::create(['name' => 'admin']);
        $kasirRole = Role::create(['name' => 'kasir']);
        
        // Membuat permission
        $permissions = [
            'lihat-dashboard',
            'kelola-pengguna',
            'kelola-produk',
            'kelola-kategori',
            'kelola-satuan',
            'kelola-transaksi',
            'lihat-laporan',
            'kelola-toko',
        ];
        
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        
        // Memberikan semua permission ke role admin
        $adminRole->givePermissionTo(Permission::all());
        
        // Memberikan permission terbatas ke role kasir
        $kasirRole->givePermissionTo([
            'lihat-dashboard',
            'kelola-transaksi',
        ]);
    }
}
