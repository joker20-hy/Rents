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
                'user_id' => 2,
                'firstname' => 'Chủ nhà',
                'lastname' => '1',
                'phone' => '+84347984078',
                'address' => 'Ngách 59 ngõ 20, đường Mỹ Đình, Nam Từ Liêm, Hà Nội',
                'date_of_birth' => '1998-01-20',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ],
            [
                'user_id' => 3,
                'firstname' => 'Chủ nhà',
                'lastname' => '2',
                'phone' => '+84347984078',
                'address' => 'Ngách 59 ngõ 20, đường Mỹ Đình, Nam Từ Liêm, Hà Nội',
                'date_of_birth' => '1998-01-20',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ],
            [
                'user_id' => 4,
                'firstname' => 'Người dùng',
                'lastname' => '1',
                'phone' => '+84347984078',
                'address' => 'Ngách 59 ngõ 20, đường Mỹ Đình, Nam Từ Liêm, Hà Nội',
                'date_of_birth' => '1998-01-20',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ],
            [
                'user_id' => 5,
                'firstname' => 'Người dùng',
                'lastname' => '2',
                'phone' => '+84347984078',
                'address' => 'Ngách 59 ngõ 20, đường Mỹ Đình, Nam Từ Liêm, Hà Nội',
                'date_of_birth' => '1998-01-20',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ],
            [
                'user_id' => 6,
                'firstname' => 'Người dùng',
                'lastname' => '3',
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
