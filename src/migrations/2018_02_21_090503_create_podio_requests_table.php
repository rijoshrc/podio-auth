<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePodioRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('podio_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->text("request")->nullable();
            $table->integer("app_id")->nullable();
            $table->integer("is_processed")->default(0);
            $table->integer("is_processing")->default(0);
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
        Schema::drop('podio_requests');
    }
}
