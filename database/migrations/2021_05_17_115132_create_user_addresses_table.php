<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->on('users')->references('id')
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
        Schema::dropIfExists('user_addresses');
    }
}
