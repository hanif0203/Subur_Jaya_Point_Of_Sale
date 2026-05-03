<?php

namespace Database\Seeders;

use App\Constants\Role;
use App\Models\Tenants\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as ModelsRole;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $this->deletePermission();
        $permissions = $this->getPermissions();
        $permissions->each(fn ($roles) => $this->savePermission($roles));

        if ($user = User::first()) {
            $user->assignRole(Role::admin);
        }
    }

    private function crudRolePermission(): array
    {
        return [
            // ========== ROLE ADMIN (FULL ACCESS) ==========
            [
                'role' => [Role::admin],
                'permissions' => [
                    'pengguna' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'kategori' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'produk' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'stok produk' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'member' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'penjualan' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'metode penjualan' => [
                        'permission' => ['atur'],
                        'guard' => ['web'],
                    ],
                    'metode pembayaran' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'laci kas' => [
                        'permission' => ['buka', 'aktifkan', 'tutup'],
                        'guard' => ['web'],
                    ],
                    'mata uang' => [
                        'permission' => ['u'],
                        'guard' => ['web'],
                    ],
                    'jabatan' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'hak akses' => [
                        'permission' => ['r'],
                        'guard' => ['web'],
                    ],
                    'aplikasi web' => [
                        'permission' => ['akses'],
                        'guard' => ['web'],
                    ],
                    'kasir' => [
                        'permission' => ['akses'],
                        'guard' => ['web'],
                    ],
                    'laporan kasir' => [
                        'permission' => ['buat'],
                        'guard' => ['web'],
                    ],
                    'laporan penjualan' => [
                        'permission' => ['buat'],
                        'guard' => ['web'],
                    ],
                    'laporan produk' => [
                        'permission' => ['buat'],
                        'guard' => ['web'],
                    ],
                    'laporan pembelian' => [
                        'permission' => ['buat'],
                        'guard' => ['web'],
                    ],
                    'impor produk' => [
                        'permission' => [''],
                        'guard' => ['web'],
                    ],
                    'pajak default' => [
                        'permission' => ['atur'],
                        'guard' => ['web'],
                    ],
                    'tentang' => [
                        'permission' => ['r', 'u'],
                        'guard' => ['web'],
                    ],
                    'detail harga awal' => [
                        'permission' => ['r'],
                        'guard' => ['web'],
                    ],
                    'pembelian' => [
                        'permission' => ['c', 'r', 'u', 'd', 'setujui'],
                        'guard' => ['web'],
                    ],
                    'stok opname' => [
                        'permission' => ['c', 'r', 'u', 'd', 'setujui'],
                        'guard' => ['web'],
                    ],
                    'piutang' => [
                        'permission' => ['r'],
                        'guard' => ['web'],
                    ],
                    'pembayaran piutang' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'voucher' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'cetak penjualan' => [
                        'permission' => ['bisa'],
                        'guard' => ['web'],
                    ],
                    'cetak label' => [
                        'permission' => ['bisa'],
                        'guard' => ['web'],
                    ],
                    'ringkasan pendapatan' => [
                        'permission' => ['r'],
                        'guard' => ['web'],
                    ],
                    'ringkasan penjualan' => [
                        'permission' => ['r'],
                        'guard' => ['web'],
                    ],
                    'ringkasan diskon' => [
                        'permission' => ['r'],
                        'guard' => ['web'],
                    ],
                    'supplier' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'meja' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'fitur flag' => [
                        'permission' => ['akses'],
                        'guard' => ['web'],
                    ],
                    'update aplikasi' => [
                        'permission' => ['bisa'],
                        'guard' => ['web']
                    ],
                    'restore aplikasi' => [
                        'permission' => ['bisa'],
                        'guard' => ['web']
                    ],
                ],
            ],
            
            // ========== ROLE KASIR (Extended Access + POS) ==========
            [
                'role' => ['kasir'],
                'permissions' => [
                    'aplikasi web' => [
                        'permission' => ['akses'],
                        'guard' => ['web'],
                    ],
                    'kasir' => [
                        'permission' => ['akses'],
                        'guard' => ['web'],
                    ],
                    'penjualan' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'produk' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'kategori' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'stok produk' => [
                        'permission' => ['c', 'r', 'u'],
                        'guard' => ['web'],
                    ],
                    'member' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'metode pembayaran' => [
                        'permission' => ['r'],
                        'guard' => ['web'],
                    ],
                    'laci kas' => [
                        'permission' => ['buka', 'tutup'],
                        'guard' => ['web'],
                    ],
                    'cetak penjualan' => [
                        'permission' => ['bisa'],
                        'guard' => ['web'],
                    ],
                    'cetak label' => [
                        'permission' => ['bisa'],
                        'guard' => ['web'],
                    ],
                    'laporan kasir' => [
                        'permission' => ['buat'],
                        'guard' => ['web'],
                    ],
                    'laporan penjualan' => [
                        'permission' => ['buat'],
                        'guard' => ['web'],
                    ],
                    'laporan produk' => [
                        'permission' => ['buat'],
                        'guard' => ['web'],
                    ],
                    'voucher' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'meja' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'piutang' => [
                        'permission' => ['r'],
                        'guard' => ['web'],
                    ],
                    'pembayaran piutang' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'ringkasan penjualan' => [
                        'permission' => ['r'],
                        'guard' => ['web'],
                    ],
                    'ringkasan pendapatan' => [
                        'permission' => ['r'],
                        'guard' => ['web'],
                    ],
                    'ringkasan diskon' => [
                        'permission' => ['r'],
                        'guard' => ['web'],
                    ],
                    'detail harga awal' => [
                        'permission' => ['r'],
                        'guard' => ['web'],
                    ],
                ],
            ],
            
            // ========== ROLE GUDANG (Inventory Management - NO POS) ==========
            [
                'role' => ['gudang'],
                'permissions' => [
                    'aplikasi web' => [
                        'permission' => ['akses'],
                        'guard' => ['web'],
                    ],
                    // TIDAK ADA 'kasir' => role gudang TIDAK BISA akses POS
                    'produk' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'kategori' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'stok produk' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'stok opname' => [
                        'permission' => ['c', 'r', 'u', 'd', 'setujui'],
                        'guard' => ['web'],
                    ],
                    'supplier' => [
                        'permission' => ['c', 'r', 'u', 'd'],
                        'guard' => ['web'],
                    ],
                    'pembelian' => [
                        'permission' => ['c', 'r', 'u', 'd', 'setujui'],
                        'guard' => ['web'],
                    ],
                    'cetak label' => [
                        'permission' => ['bisa'],
                        'guard' => ['web'],
                    ],
                    'laporan produk' => [
                        'permission' => ['buat'],
                        'guard' => ['web'],
                    ],
                    'laporan pembelian' => [
                        'permission' => ['buat'],
                        'guard' => ['web'],
                    ],
                    'detail harga awal' => [
                        'permission' => ['r'],
                        'guard' => ['web'],
                    ],
                    'impor produk' => [
                        'permission' => [''],
                        'guard' => ['web'],
                    ],
                    'penjualan' => [
                        'permission' => ['r'], // hanya lihat (untuk cek stok terjual)
                        'guard' => ['web'],
                    ],
                    'ringkasan penjualan' => [
                        'permission' => ['r'], // lihat dashboard
                        'guard' => ['web'],
                    ],
                ],
            ],
        ];
    }

    private function normalizeCrudPermission()
    {
        $normalize = [];
        foreach ($this->crudRolePermission() as $permissions) {
            foreach ($permissions['permissions'] as $feature => $crud) {
                $actions = [];
                for ($i = 0; $i < count($crud['permission']); $i++) {
                    $action = '';
                    switch ($crud['permission'][$i]) {
                        case 'c':
                            $action = "buat $feature";
                            break;
                        case 'r':
                            $action = "lihat $feature";
                            break;
                        case 'u':
                            $action = "ubah $feature";
                            break;
                        case 'd':
                            $action = "hapus $feature";
                            break;
                        default:
                            $action = $crud['permission'][$i]." $feature";
                            break;
                    }
                    $actions[$i] = $action;
                }
                foreach ($actions as $action) {
                    $normalize[] = [
                        'role' => $permissions['role'],
                        'action' => trim($action),
                        'guard' => $crud['guard'],
                    ];
                }
            }
        }

        return $normalize;
    }

    private function getPermissions(): Collection
    {
        return collect(array_merge($this->normalizeCrudPermission(), []));
    }

    private function savePermission($roles): void
    {
        foreach ($roles['guard'] as $guard) {
            $permission = Permission::firstOrCreate(['name' => $roles['action'], 'guard_name' => $guard]);
            $this->givePermissionToRole($roles['role'], $permission);
        }
    }

    private function givePermissionToRole($role, $permission): void
    {
        /** @var ModelsRole $role */
        $role = ModelsRole::where('name', $role[0])->firstOrCreate(['name' => $role[0]]);
        $role->permissions()->syncWithoutDetaching($permission);
    }

    private function deletePermission()
    {
        Permission::query()
            ->whereNotIn('name', $this->getPermissions()->pluck('action'))
            ->where('guard_name', 'web')
            ->delete();
    }
}