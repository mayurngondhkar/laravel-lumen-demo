<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'user1',
            'email' => 'user1@test.com',
            'password' => password_hash('password', PASSWORD_BCRYPT),
        ]);

        DB::table('users')->insert([
            'name' => 'user2',
            'email' => 'user2@test.com',
            'password' => password_hash('password', PASSWORD_BCRYPT),
        ]);
    }
}
