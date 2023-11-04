<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    protected $permissionsByRole = [
        'admin' => [
            'view users', 'view user rate', 'create user', 'edit user', 'archive user', 'restore user',
            'view labels', 'create label', 'edit label', 'archive label', 'restore label',
            'view roles', 'create role', 'edit role', 'archive role', 'restore role',
            'view owner company', 'edit owner company',
            'view client users', 'create client user', 'edit client user', 'archive client user', 'restore client user',
            'view client companies', 'create client company', 'edit client company', 'archive client company', 'restore client company',
        ],
        'manager' => ['view users'],
        'developer' => [],
        'designer' => [],
        'client' => [],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $insertPermissions = fn ($role) => collect($this->permissionsByRole[$role])
            ->map(function ($name) {
                $permission = DB::table('permissions')->where('name', $name)->first();

                return $permission
                    ? $permission->id
                    : DB::table('permissions')
                        ->insertGetId([
                            'name' => $name,
                            'guard_name' => 'web',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
            })
            ->toArray();

        $permissionIdsByRole = [
            'admin' => $insertPermissions('admin'),
            'manager' => $insertPermissions('manager'),
            'developer' => $insertPermissions('developer'),
            'designer' => $insertPermissions('designer'),
            'client' => $insertPermissions('client'),
        ];

        foreach ($permissionIdsByRole as $role => $permissionIds) {
            $role = Role::whereName($role)->first();

            DB::table('role_has_permissions')
                ->insert(
                    collect($permissionIds)->map(fn ($id) => [
                        'role_id' => $role->id,
                        'permission_id' => $id,
                    ])->toArray()
                );
        }

        Artisan::call('cache:clear');
    }
}
