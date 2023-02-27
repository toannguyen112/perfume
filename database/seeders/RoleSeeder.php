<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=RoleSeeder
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name' => 'Super Admin', 'guard_name' => 'admin'],
            ['name' => 'Editor', 'guard_name' => 'admin']
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate($role);
        }
        $this->createAdmin();
        $this->addPermissions();
    }

    private function firstAdmin()
    {
        return Admin::where('email', 'admin@gmail.com')->first();
    }

    private function createAdmin()
    {
        if (empty($this->firstAdmin())) {
            Admin::create(
                [
                    'email' => 'admin@gmail.com',
                    'status' => 1,
                    'name' => 'admin',
                    'password' => Hash::make('admin@gmail.com')
                ]
            );
        }
    }

    private function addPermissions()
    {
        $permissions = [];
        foreach (Route::getRoutes() as $route) {
            $routeName = $route->getName();
            if (contains($routeName, 'sidebar') && contains($routeName, 'index')) {
                if (!in_array($routeName, $permissions)) {
                    $permissions[] = $routeName;
                }
            }
        }

        $role = Role::first();
        foreach ($permissions as $permission) {
            $new_permission = Permission::updateOrCreate([
                'name' => $permission,
                'guard_name' => $role->guard_name
            ]);
            $new_permission->assignRole($role);
        }

        $this->firstAdmin()->assignRole($role);
    }
}
