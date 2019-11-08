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
        $this->call(UsersTableSeeder::class);
        $this->call(PermTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(RolePermTableSeeder::class);
        $this->call(RoleUserTableSeeder::class);
    }
}
