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
        Schema::create('location', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business');
            $table->string('address1');
            $table->string('address2')->nullable(true);
            $table->string('address3')->nullable(true);
            $table->string('city');
            $table->integer('zip_code');
            $table->string('country');
            $table->string('state');
            $table->string('display_address');
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
        Schema::dropIfExists('location_tables');
    }
};
