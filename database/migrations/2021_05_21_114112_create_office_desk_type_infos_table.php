<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeDeskTypeInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_desk_type_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('office_id');
            $table->foreign('office_id')->on('offices')->references('id')
           ->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('office_desk_type_id');
            $table->foreign('office_desk_type_id')->on('office_desk_types')->references('id')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->string('cost',10);
            $table->enum('discount_type',['flat rate','percentage']);
            $table->string('discount',5)->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('no_of_desk')->nullable();
            $table->tinyInteger('available_desk')->nullable();
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
        Schema::dropIfExists('office_desk_type_infos');
    }
}
