<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('house_id');
            $table->foreign('house_id')->references('id')->on('houses');
            $table->string('name');
            $table->float('avg_rate', 4, 2)->default(0.00);
            $table->unsignedInteger('rate_count')->default(0);
            $table->float('acreage');
            $table->integer('price');
            $table->unsignedTinyInteger('cycle')->default(1);
            $table->text('images');
            $table->text('description')->nullable();
            //should be boolean
            $table->unsignedTinyInteger('status')->default(config('const.ROOM_STATUS.waiting'));
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
