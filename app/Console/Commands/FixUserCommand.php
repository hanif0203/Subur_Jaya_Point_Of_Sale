<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenants\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FixUserCommand extends Command
{
    protected $signature = 'fix:user';
    protected $description = 'Fix kasir user and role';

    public function handle()
    {
        $this->info('🔧 Fixing user and role...');
        
        try {
            // Hapus role lama
            $this->info('Deleting old roles...');
            DB::table('roles')->whereIn('name', ['kasir ', 'Gudang', 'kasir', 'gudang'])->delete();
            DB::table('role_has_permissions')->truncate();
            
            // Buat role baru
            $this->info('Creating new role...');
            $kasir = Role::firstOrCreate(
                ['name' => 'kasir', 'guard_name' => 'web']
            );
            
            // Assign permissions
            $this->info('Assigning permissions...');
            $permissions = Permission::where('guard_name', 'web')->get();
            
            if ($permissions->isEmpty()) {
                $this->error('❌ No permissions found with guard_name=web');
                return 1;
            }
            
            $kasir->syncPermissions($permissions);
            $this->info("✅ Assigned {$permissions->count()} permissions to kasir role");
            
            // Hapus user lama dengan FORCE DELETE
            $this->info('Deleting old user...');
            DB::table('model_has_roles')->where('model_type', 'App\\Models\\Tenants\\User')
                ->whereIn('model_id', function($query) {
                    $query->select('id')
                        ->from('users')
                        ->where('email', 'kasirsuburjaya@gmail.com');
                })->delete();
            
            DB::table('users')->where('email', 'kasirsuburjaya@gmail.com')->delete();
            
            // Buat user baru
            $this->info('Creating new user...');
            $user = User::create([
                'name' => 'Kasir Subur Jaya',
                'email' => 'kasirsuburjaya@gmail.com',
                'password' => Hash::make('password123'),
                'is_owner' => false,
            ]);
            
            $user->assignRole('kasir');
            
            // Verifikasi
            $this->info('');
            $this->info('✅ SUCCESS!');
            $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            $this->info('📧 Email: kasirsuburjaya@gmail.com');
            $this->info('🔑 Password: password123');
            $this->info('👤 Role: ' . $user->getRoleNames()->first());
            $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            
            // Test password
            if (Hash::check('password123', $user->password)) {
                $this->info('✅ Password verification: OK');
            } else {
                $this->error('❌ Password verification: FAILED');
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
    }
}
