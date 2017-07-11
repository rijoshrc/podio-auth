<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePodioAppAuthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('podio_app_auths', function (Blueprint $table) {
            $table->increments('id');
            $table->string('app_name');
            $table->integer('app_id')->unique();
            $table->string('app_secret');
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
        Schema::drop('podio_app_auths');
    }
}
