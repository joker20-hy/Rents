<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedSmallInteger('province_id');
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->unsignedMediumInteger('district_id');
            $table->foreign('district_id')->references('id')->on('districts');
            $table->unsignedBigInteger('area_id')->nullable();
            $table->foreign('area_id')->references('id')->on('areas');
            $table->string('address');
            $table->double('latitude', 9, 6)->nullable();
            $table->double('longitude', 9, 6)->nullable();
            $table->float('avg_rate', 4, 2)->default(0.00);
            $table->unsignedInteger('rate_count')->default(0);
            $table->unsignedBigInteger('price')->nullable();
            $table->boolean('rent')->default(false);
            $table->float('acreage')->nullable();
            $table->text('images')->nullable();
            $table->string('direction')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('houses');
    }
}
