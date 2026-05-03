<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Inisialisasi tenant (sesuaikan dengan tenant ID Anda)
$tenant = \App\Models\Tenant::first();
if ($tenant) {
    $tenant->run(function () {
        // Hapus role lama
        \Illuminate\Support\Facades\DB::table('roles')->whereIn('name', ['kasir ', 'Gudang', 'kasir', 'gudang'])->delete();
        \Illuminate\Support\Facades\DB::table('role_has_permissions')->truncate();
        
        // Buat role baru
        $kasir = \Spatie\Permission\Models\Role::create([
            'name' => 'kasir',
            'guard_name' => 'web'
        ]);
        
        // Assign permissions
        $permissions = \Spatie\Permission\Models\Permission::where('guard_name', 'web')->get();
        $kasir->syncPermissions($permissions);
        
        // Hapus user lama
        \App\Models\Tenants\User::where('email', 'kasirsuburjaya@gmail.com')->delete();
        
        // Buat user baru
        $user = \App\Models\Tenants\User::create([
            'name' => 'Kasir Subur Jaya',
            'email' => 'kasirsuburjaya@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'is_owner' => false,
        ]);
        
        $user->assignRole('kasir');
        
        echo "✅ BERHASIL!\n";
        echo "Email: kasirsuburjaya@gmail.com\n";
        echo "Password: password123\n";
        echo "Role: " . $user->getRoleNames()->first() . "\n";
    });
} else {
    echo "❌ Tenant tidak ditemukan!\n";
}



