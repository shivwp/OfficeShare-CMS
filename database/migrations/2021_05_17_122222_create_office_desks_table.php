<?php

use App\Office;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeDesksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_desks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('office_id');
            $table->foreign("office_id")->on('offices')->references('id')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('office_desk_type_id');
            $table->foreign('office_desk_type_id')->on('office_desk_types')->references('id')
           ->onDelete('cascade')->onUpdate('cascade');

            $table->string('desk_id',20)->nullable();
            $table->tinyInteger('status')->default('1');
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
        Schema::dropIfExists('office_desks');
    }
}
