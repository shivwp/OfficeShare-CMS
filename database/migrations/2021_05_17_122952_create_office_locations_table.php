<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('office_id');
            $table->foreign('office_id')->on('offices')->references('id')
            ->onDelete('cascade')->onUpdate('cascade');
            
            $table->integer('country_id');
            $table->foreign('country_id')->on('countries')->references('id')
            ->onDelete('cascade')->onUpdate('cascade');
 
            $table->integer('state_id');
            $table->foreign('state_id')->on('states')->references('id')
            ->onDelete('cascade')->onUpdate('cascade');
 
            $table->integer('city_id');
            $table->foreign('city_id')->on('cities')->references('id')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->string('postcode',25);
            $table->text('address')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('office_locations');
    }
}
