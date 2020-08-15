<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->insert([
            [
                'name' => 'Điện (tính theo số)',
                'type' => config('const.SERVICE_TYPE.PER_UNIT'),
                'unit' => 'Số',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ],
            [
                'name' => 'Nước (theo người)',
                'type' => config('const.SERVICE_TYPE.PER_UNIT'),
                'unit' => 'người',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ],
            [
                'name' => 'Nước (theo khối)',
                'type' => config('const.SERVICE_TYPE.PERIODIC'),
                'unit' => 'khối',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ],
            [
                'name' => 'Internet',
                'type' => config('const.SERVICE_TYPE.PERIODIC'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ],
            [
                'name' => 'Vệ sinh',
                'type' => config('const.SERVICE_TYPE.PER_UNIT'),
                'unit' => 'người',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ],
            [
                'name' => 'Thang máy',
                'type' => config('const.SERVICE_TYPE.PER_UNIT'),
                'unit' => 'người',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ]
        ]);
    }
}
