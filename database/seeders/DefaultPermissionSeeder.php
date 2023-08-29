<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DefaultPermissionSeeder extends Seeder
{
    /**
     * 新增權限
     * 請參考DefaultPermissionSeeder
     * Run the database seeds.
     */
    public function run(): void
    {
        $guardName = config('auth.defaults.guard');

        // 常用:viewAny,view,create,update,delete
        Permission::firstOrCreate(['name' => '管理員-任意檢視', 'guard_name' => $guardName]);
        Permission::firstOrCreate(['name' => '管理員-檢視', 'guard_name' => $guardName]);
        Permission::firstOrCreate(['name' => '管理員-新增', 'guard_name' => $guardName]);
        Permission::firstOrCreate(['name' => '管理員-更新', 'guard_name' => $guardName]);
        Permission::firstOrCreate(['name' => '管理員-刪除', 'guard_name' => $guardName]);

        // 不常使用:forceDelete,restore
        Permission::firstOrCreate(['name' => '管理員-強制刪除', 'guard_name' => $guardName]);
        Permission::firstOrCreate(['name' => '管理員-還原刪除', 'guard_name' => $guardName]);

        // 特別:deleteAny,restoreAny,forceDeleteAny
        Permission::firstOrCreate(['name' => '管理員-任意刪除', 'guard_name' => $guardName]);
        Permission::firstOrCreate(['name' => '管理員-任意強制刪除', 'guard_name' => $guardName]);
        Permission::firstOrCreate(['name' => '管理員-任意還原刪除', 'guard_name' => $guardName]);

        // 新增規則
        $role = Role::firstOrCreate(['name' => '管理員', 'guard_name' => $guardName]);

        // 權限指定到規則
        $role->givePermissionTo(Permission::all());

        // 指定規則到管理員
        Admin::find(1)->assignRole('管理員');
    }
}