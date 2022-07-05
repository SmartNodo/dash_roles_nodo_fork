<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultedCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consulted_credits', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('idState_state');
            $table->unsignedBigInteger('user_id');

            $table->string('nss', 14)->nullable();
            $table->string('creditNumber', 12);
            $table->enum('status', ['pendiente', 'consultado', 'bloqueado'])->nullable();
            $table->string('balance')->nullable();
            $table->string('email')->nullable();
            $table->string('description')->nullable();
            $table->string('isError')->nullable();
            $table->date('consultedDate')->nullable();
            $table->date('confirmedDate')->nullable();

            $table->foreign('idState_state')->references('idState')->on('states');
            $table->foreign('user_id')->references('id')->on('users');

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
        Schema::dropIfExists('consulted_credits');
    }
}
