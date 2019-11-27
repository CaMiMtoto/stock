<?php

use App\Role;
use App\User;
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
        $user = new User();
        $user->name = 'Jean Paul CaMi';
        $user->role_id = Role::where('name','admin')->first()->id;
        $user->email = 'admin@gmail.com';
        $user->password = bcrypt('password');
        $user->save();
    }
}
