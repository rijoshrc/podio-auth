<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePodioHooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('podio_hooks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ref_id');
            $table->string('ref_type')->nullable();
            $table->integer('hook_id')->nullable();
            $table->string('type')->nullable();
            $table->string('url')->nullable();
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
        Schema::drop('podio_hooks');
    }
}
