<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuevesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('queves', function (Blueprint $table) {
        //     $table->id();
        //     $table->integer('user_id')->unsigned();
        //     $table->smallInteger('idState');
        //     $table->integer('nss');
        //     $table->smallInteger('status')->default('0');
        //     $table->integer('balance')->nullable()->default('0');
        //     $table->string('email')->nullable();
        //     $table->string('description')->nullable();
        //     $table->string('isError')->nullable();
        //     $table->timestamp('dateOfInsert')->nullable();
        //     $table->timestamp('dateOfConfirm')->nullable();
        //     $table->timestamp('dateLastUpdate')->nullable();
        //     $table->foreign('user_id')->references('id')->on('users');
        // });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('queves');
    }
}
