<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::insert("INSERT INTO roles (id, name, display, created_at, updated_at) VALUES (?,?,?,?,?);",
            [1, 'admin', 'Administrator', '2019-11-27 11:12:46', '2019-11-27 11:12:46']);
        DB::insert("INSERT INTO roles (id, name, display, created_at, updated_at) VALUES (?,?,?,?,?);",
            [2, 'cashier', 'Cashier', '2019-11-27 11:12:46', '2019-11-27 11:12:46']);
        DB::insert("INSERT INTO roles (id, name, display, created_at, updated_at) VALUES (?,?,?,?,?);",
            [3, 'keeper', 'Store Keeper', '2019-11-27 11:12:46', '2019-11-27 11:12:46']);
        DB::insert("INSERT INTO roles (id, name, display, created_at, updated_at) VALUES (?,?,?,?,?);",
            [4, 'manager', 'Manager', '2019-11-27 11:12:46', '2019-11-27 11:12:46']);
    }
}
