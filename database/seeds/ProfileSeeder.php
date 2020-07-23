<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profiles')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'firstname' => 'Tiến Mạnh',
                'lastname' => 'Phạm',
                'phone' => '+84347984078',
                'address' => 'Ngách 59 ngõ 20, đường Mỹ Đình, Nam Từ Liêm, Hà Nội',
                'date_of_birth' => '1998-01-20',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'firstname' => 'joker',
                'lastname' => 'Phạm',
                'phone' => '+84347984078',
                'address' => 'Ngách 59 ngõ 20, đường Mỹ Đình, Nam Từ Liêm, Hà Nội',
                'date_of_birth' => '1998-01-20',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ],
            [
                'id' => 3,
                'user_id' => 3,
                'firstname' => 'Chủ nhà',
                'lastname' => '1',
                'phone' => '+84347984078',
                'address' => 'Ngách 59 ngõ 20, đường Mỹ Đình, Nam Từ Liêm, Hà Nội',
                'date_of_birth' => '1998-01-20',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ]
        ]);
    }
}
