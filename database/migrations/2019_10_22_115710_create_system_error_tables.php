<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateSystemErrorTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		DB::beginTransaction();
		
        Schema::create('system_errors', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->unsignedBigInteger('system_id');
			$table->unsignedBigInteger('user_id');
			$table->unsignedBigInteger('station_id');
			$table->integer('number');
			$table->string('description');
			$table->string('solution')->nullable();
			$table->dateTime('from');
			$table->dateTime('to');
			$table->date('date_created');
			$table->time('time_created');
			$table->unsignedBigInteger('state_id');
			$table->string('remarks')->nullable();
            $table->timestamps();
			$table->softDeletes();
			$table->foreign('system_id')->references('id')->on('systems')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('station_id')->references('id')->on('stations')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('state_id')->references('id')->on('states')->onUpdate('cascade')->onDelete('cascade');
        });
		
		Schema::create('system_error_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->unsignedBigInteger('error_id');
			$table->unsignedBigInteger('user_id');
			$table->string('email');
            $table->timestamps();
			$table->softDeletes();
			$table->foreign('error_id')->references('id')->on('errors')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
		
		DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_error_notifications');
        Schema::dropIfExists('system_errors');
    }
}
