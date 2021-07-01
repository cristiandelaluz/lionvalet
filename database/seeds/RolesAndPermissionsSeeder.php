<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'admin']);
        // Permissions
        // $role->givePermissionTo('edit clients');
        // $role->givePermissionTo('edit quotes');

        $role = Role::create(['name' => 'client']);
        // Permissions

        $user = User::create(['name' => 'Admin', 'email' => 'admin@lionvalet.com', 'password' => Hash::make('lionvalet2021')]);
        $user->assignRole('admin');
    }
}
