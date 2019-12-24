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
			$table->integer('number');
			$table->string('description');
			$table->string('impact');
			$table->string('remarks')->nullable();
			$table->boolean('responsibility');
            $table->timestamps();
			$table->softDeletes();
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('function_id')->references('id')->on('functions')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('station_id')->references('id')->on('stations')->onUpdate('cascade')->onDelete('cascade');
        });
		
		Schema::create('status', function(Blueprint $table){
			$table->bigIncrements('id');
			$table->uuid('uuid');
			$table->unsignedBigInteger('state_id');
			$table->unsignedBigInteger('user_id');
			$table->string('remarks')->nullable();
            $table->timestamps();
			$table->softDeletes();
			$table->foreign('state_id')->references('id')->on('states')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
		});
		
		Schema::create('error_status', function(Blueprint $table){
			$table->unsignedBigInteger('error_id');
			$table->unsignedBigInteger('status_id');
			$table->foreign('error_id')->references('id')->on('errors')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('status_id')->references('id')->on('status')->onUpdate('cascade')->onDelete('cascade');
		});
		
		Schema::create('affected_products', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->unsignedBigInteger('user_id');
			$table->unsignedBigInteger('error_id');
			$table->unsignedBigInteger('product_id');
			$table->string('product_identification')->nullable();
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
			$table->unsignedBigInteger('station_id');
			$table->boolean('source');
			$table->string('corrective_action');
			$table->string('cause');
			$table->string('remarks')->nullable();
            $table->timestamps();
			$table->softDeletes();
			$table->foreign('error_id')->references('id')->on('errors')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('station_id')->references('id')->on('stations')->onUpdate('cascade')->onDelete('cascade');
        });
		
		Schema::create('error_correction_status', function(Blueprint $table){
			$table->unsignedBigInteger('error_correction_id');
			$table->unsignedBigInteger('status_id');
			$table->foreign('error_correction_id')->references('id')->on('error_corrections')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('status_id')->references('id')->on('status')->onUpdate('cascade')->onDelete('cascade');
		});
		
		Schema::create('aio_errors', function(Blueprint $table){
			$table->bigIncrements('id');
			$table->uuid('uuid');
			$table->unsignedBigInteger('error_correction_id');
			$table->unsignedBigInteger('user_id');
			$table->unsignedBigInteger('originator_id');
			$table->timestamps();
			$table->softDeletes();
			$table->foreign('error_correction_id')->references('id')->on('error_corrections')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('originator_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
			
		});
		
		Schema::create('originator_reactions', function(Blueprint $table){
			$table->bigIncrements('id');
			$table->uuid('uuid');
			$table->unsignedBigInteger('error_correction_id');
			$table->boolean('status');
			$table->string('remarks')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->foreign('error_correction_id')->references('id')->on('error_corrections')->onUpdate('cascade')->onDelete('cascade');
		});
		
		Schema::create('external_errors', function(Blueprint $table){
			$table->bigIncrements('id');
			$table->uuid('uuid');
			$table->unsignedBigInteger('error_correction_id');
			$table->unsignedBigInteger('user_id');
			$table->string('description');
			$table->timestamps();
			$table->softDeletes();
			$table->foreign('error_correction_id')->references('id')->on('error_corrections')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
		});
		
		Schema::create('supervisor_reactions', function(Blueprint $table){
			$table->bigIncrements('id');
			$table->uuid('uuid');
			$table->unsignedBigInteger('error_correction_id');
			$table->boolean('status');
			$table->string('remarks')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->foreign('error_correction_id')->references('id')->on('error_corrections')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('supervisor_reactions');
        Schema::dropIfExists('external_errors');
        Schema::dropIfExists('originator_reactions');
        Schema::dropIfExists('aio_errors');
        Schema::dropIfExists('error_correction_status');
        Schema::dropIfExists('error_corrections');
        Schema::dropIfExists('notification_recipients');
        Schema::dropIfExists('message_response');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('error_notifications');
        Schema::dropIfExists('affected_products');
        Schema::dropIfExists('error_status');
        Schema::dropIfExists('status');
        Schema::dropIfExists('errors');
    }
}
