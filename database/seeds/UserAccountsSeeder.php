<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Role;

class UserAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Super Admin',
                'email' => 'abdullah.basit@hotmail.com',
                'username' => 'nimda',
                'password' => bcrypt('abdullah')
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'username' => 'admin',
                'password' => bcrypt('admin123')
            ]
        ];

        DB::table('users')->insert($data);

        $rolesData = [
            ['name' => 'superadmin', 'desc' => 'Super Admin'],
            ['name' => 'admin', 'desc' => 'Administrator'],
            ['name' => 'agent', 'desc' => 'Agent'],
            ['name' => 'supervisor', 'desc' => 'Supervisor'],
            ['name' => 'rating', 'desc' => 'Rating User']
        ];

        DB::table('roles')->insert($rolesData);

        $role = Role::where("name", "superadmin")->first();
        $roleAdmin = Role::where("name", "admin")->first();
        $user = User::where("name", 'Super Admin')->first()->roles()->attach($role->id);
        $userAdmin = User::where("name", 'Admin')->first()->roles()->attach($roleAdmin->id);
    }
}
