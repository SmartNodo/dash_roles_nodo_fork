<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_keys', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('idState_state');

            $table->integer('status')->nullable();

            $table->string('user', 30);
            $table->string('pass', 30);
            $table->string('error', 60)->nullable();


            $table->foreign('idState_state')->references('idState')->on('states');


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
        Schema::dropIfExists('access_keys');
    }
}
