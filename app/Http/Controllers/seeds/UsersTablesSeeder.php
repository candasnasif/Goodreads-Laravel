<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::create([
            'name'              =>  'test3',
            'email'             =>  'test3@gmail.com',
            'password'          =>  Hash::make('password'),
            'typeID'            =>  1,
            'remember_token'    =>  str_random(10)
        ]);
    }
}
