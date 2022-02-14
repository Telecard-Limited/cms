<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(FakerSeeder::class);
        $this->call(UserAccountsSeeder::class);
        $this->call(SettingsSeeder::class);
        // $this->call(RatingSeeder::class);
    }
}
