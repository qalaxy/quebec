<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateErrorTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		DB::beginTransaction();
		
        Schema::create('errors', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->unsignedBigInteger('user_id');
			$table->unsignedBigInteger('function_id');
			$table->unsignedBigInteger('station_id');
			$table->date('date_created');
			$table->time('time_created');
			$table->string('description');
			$table->string('impact');
			$table->string('remarks')->nullable();
			$table->unsignedBigInteger('error_status_id');
			$table->boolean('responsibility');
            $table->timestamps();
			$table->softDeletes();
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('function_id')->references('id')->on('functions')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('station_id')->references('id')->on('stations')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('error_status_id')->references('id')->on('error_status')->onUpdate('cascade')->onDelete('cascade');
        });
		
		Schema::create('affected_products', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->unsignedBigInteger('user_id');
			$table->unsignedBigInteger('error_id');
			$table->unsignedBigInteger('product_id');
			$table->unsignedBigInteger('product_identification')->nullable();
			$table->string('remarks')->nullable();
            $table->timestamps();
			$table->softDeletes();
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('error_id')->references('id')->on('errors')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
        });
		
		Schema::create('error_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->unsignedBigInteger('error_id');
			$table->unsignedBigInteger('station_id');
			$table->unsignedBigInteger('user_id');
			$table->boolean('status');
            $table->timestamps();
			$table->softDeletes();
			$table->foreign('error_id')->references('id')->on('errors')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('station_id')->references('id')->on('stations')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
		
		Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->unsignedBigInteger('error_notification_id');
			$table->unsignedBigInteger('user_id');
			$table->string('text');
            $table->timestamps();
			$table->softDeletes();
			$table->foreign('error_notification_id')->references('id')->on('error_notifications')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
		
		Schema::create('message_response',function(Blueprint $table){
			$table->unsignedBigInteger('message_id');
            $table->unsignedBigInteger('response_id');

            $table->foreign('message_id')->references('id')->on('messages')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('response_id')->references('id')->on('messages')->onUpdate('cascade')->onDelete('cascade');
			
		});
		
		Schema::create('notification_recipients', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->unsignedBigInteger('error_notification_id');
			$table->unsignedBigInteger('user_id');
            $table->timestamps();
			$table->softDeletes();
			$table->foreign('error_notification_id')->references('id')->on('error_notifications')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
		
		Schema::create('error_corrections', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->unsignedBigInteger('error_id');
			$table->unsignedBigInteger('user_id');
			$table->unsignedBigInteger('originator_id');
			$table->unsignedBigInteger('station_id');
			$table->date('date_created');
			$table->time('time_created');
			$table->string('corrective_action');
			$table->string('cause');
			$table->string('remarks')->nullable();
            $table->timestamps();
			$table->softDeletes();
			$table->foreign('error_id')->references('id')->on('errors')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('originator_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('station_id')->references('id')->on('stations')->onUpdate('cascade')->onDelete('cascade');
        });
		
		Schema::create('recipients', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->unsignedBigInteger('station_id');
			$table->unsignedBigInteger('user_id');
            $table->timestamps();
			$table->softDeletes();
			$table->foreign('station_id')->references('id')->on('stations')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('recipients');
        Schema::dropIfExists('error_corrections');
        Schema::dropIfExists('notification_recipients');
        Schema::dropIfExists('message_response');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('error_notifications');
        Schema::dropIfExists('affected_products');
        Schema::dropIfExists('errors');
    }
}
