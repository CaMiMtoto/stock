<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new \App\User();
        $user->name = 'Jean Paul CaMi';
        $user->role = 'admin';
        $user->email = 'admin@gmail.com';
        $user->password = bcrypt('password');
        $user->save();
    }
}
