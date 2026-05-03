<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

foreach (\Spatie\Permission\Models\Permission::where('guard_name', 'sanctum')->get() as $permission) {
    $webPermission = \Spatie\Permission\Models\Permission::where('name', $permission->name)
        ->where('guard_name', 'web')
        ->first();
    
    if (!$webPermission) {
        \Spatie\Permission\Models\Permission::create([
            'name' => $permission->name, 
            'guard_name' => 'web'
        ]);
    }
}

$webPermissions = \Spatie\Permission\Models\Permission::where('guard_name', 'web')->get();

foreach (\Spatie\Permission\Models\Role::all() as $role) {
    $role->syncPermissions($webPermissions);
}

echo "✅ DONE!\n";
