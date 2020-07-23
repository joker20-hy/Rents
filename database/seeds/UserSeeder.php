<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'joker',
                'email' => 'phamtienmanh20@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('20011998'),
                'role' => config('const.USER.ROLE.ADMIN'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'batman',
                'email' => 'phamtienmanh198@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('12345678'),
                'role' => config('const.USER.ROLE.NORMAL'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'chu nha 1',
                'email' => 'manhpt@hblab.vn',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('20011998'),
                'role' => config('const.USER.ROLE.OWNER'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
