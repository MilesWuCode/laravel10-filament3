<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PolicySeeder extends Seeder
{
    /**
     * 新增權限
     * 請參考DefaultPermissionSeeder
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = '用戶';

        $guardName = config('auth.defaults.guard');

        // 常用:viewAny,view,create,update,delete
        Permission::firstOrCreate(['name' => $name.'-任意檢視', 'guard_name' => $guardName]);
        Permission::firstOrCreate(['name' => $name.'-檢視', 'guard_name' => $guardName]);
        Permission::firstOrCreate(['name' => $name.'-新增', 'guard_name' => $guardName]);
        Permission::firstOrCreate(['name' => $name.'-更新', 'guard_name' => $guardName]);
        Permission::firstOrCreate(['name' => $name.'-刪除', 'guard_name' => $guardName]);

        // 不常使用:forceDelete,restore
        Permission::firstOrCreate(['name' => $name.'-強制刪除', 'guard_name' => $guardName]);
        Permission::firstOrCreate(['name' => $name.'-還原刪除', 'guard_name' => $guardName]);

        // 特別:deleteAny,restoreAny,forceDeleteAny
        Permission::firstOrCreate(['name' => $name.'-任意刪除', 'guard_name' => $guardName]);
        Permission::firstOrCreate(['name' => $name.'-任意強制刪除', 'guard_name' => $guardName]);
        Permission::firstOrCreate(['name' => $name.'-任意還原刪除', 'guard_name' => $guardName]);
    }
}
