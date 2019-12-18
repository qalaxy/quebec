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
        $this->call(LevelsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(PermTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(RolePermTableSeeder::class);
        $this->call(RoleUserTableSeeder::class);
        $this->call(OfficesTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(StationsTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(FunctionsTableSeeder::class);
        $this->call(FunctionProductTableSeeder::class);
        $this->call(StationFunctionTableSeeder::class);
        $this->call(EmailsTableSeeder::class);
        $this->call(StationEmailTableSeeder::class);
        $this->call(RecipientTableSeeder::class);
    }
}
