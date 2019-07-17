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
            ]
        ];

        DB::table('users')->insert($data);

        $rolesData = [
            ['name' => 'superadmin', 'desc' => 'Super Admin'],
            ['name' => 'admin', 'desc' => 'Administrator'],
            ['name' => 'agent', 'desc' => 'Agent'],
            ['name' => 'supervisor', 'desc' => 'Supervisor']
        ];

        DB::table('roles')->insert($rolesData);

        $role = Role::where("name", "superadmin")->first();
        $user = User::where("name", 'Super Admin')->first()->roles()->attach($role->id);
    }
}
