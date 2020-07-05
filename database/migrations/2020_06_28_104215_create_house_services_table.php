<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHouseServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house_services', function (Blueprint $table) {
            $table->unsignedBigInteger('house_id');
            $table->foreign('house_id')->references('id')->on('houses');
            $table->unsignedInteger('service_id');
            $table->foreign('service_id')->references('id')->on('services');
            $table->double('price')->default(0.0);
            $table->unsignedTinyInteger('type')->default(config('const.SERVICE_TYPE.PRICE_FIX'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('house_services');
    }
}
