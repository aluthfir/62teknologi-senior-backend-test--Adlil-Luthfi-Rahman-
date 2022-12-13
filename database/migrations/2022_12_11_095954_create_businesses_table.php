<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('alias');
            $table->string('name');
            $table->string('image_url');
            $table->string('is_closed');
            $table->string('url');
            $table->string('review_count');
            $table->float('rating');
            $table->string('transactions')->nullable(true);
            $table->string('price')->nullable(true);
            $table->string('phone');
            $table->string('display_phone');
            $table->double('distance');
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
        Schema::dropIfExists('businesses');
    }
};
