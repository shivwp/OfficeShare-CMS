<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomePageSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_page_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_module')->nullable();
            $table->string('discount',10)->nullable();
            $table->string('product_category')->nullable();
            $table->text('content_title')->nullable();
            $table->text('contents')->nullable();
            $table->longText('images')->nullable();
            $table->string('attributes')->nullable();
            $table->string('content_position')->nullable();
            $table->string('content_priority')->nullable();
            $table->integer('total_product_to_show')->nullable();
            $table->integer('total_product_in_row')->nullable();
            $table->longText('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
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
        Schema::dropIfExists('home_page_settings');
    }
}
