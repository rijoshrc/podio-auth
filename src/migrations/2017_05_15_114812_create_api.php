<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id')->unique();
            $table->string('client_secret')->nullable();
            $table->string('refresh_token')->nullable();
            $table->integer('current')->default(0);
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
        Schema::dropIfExists('app_auth');
    }
}
