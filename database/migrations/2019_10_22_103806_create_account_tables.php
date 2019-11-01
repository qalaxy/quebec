<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAccountTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		DB::beginTransaction();
		
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->unsignedBigInteger('user_id');
			$table->string('first_name');
			$table->string('middle_name');
			$table->string('last_name');
			$table->string('p_number');
			$table->string('gender');
            $table->timestamps();
			$table->softDeletes();
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
		
		
		Schema::create('stations', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->unsignedBigInteger('office_id');
			$table->string('name');
			$table->string('abbreviation');
            $table->timestamps();
			$table->softDeletes();
			$table->foreign('office_id')->references('id')->on('offices')->onUpdate('cascade')->onDelete('cascade');
        });
		
		
		Schema::create('account_station', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->unsignedBigInteger('station_id');
			$table->unsignedBigInteger('account_id');
			$table->dateTime('from');
			$table->dateTime('to');
			$table->boolean('status');
            $table->timestamps();
			$table->softDeletes();
			$table->foreign('station_id')->references('id')->on('stations')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
        });
		
		Schema::create('phone_numbers', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->string('number');
            $table->timestamps();
			$table->softDeletes();
        });
		
		Schema::create('emails', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->string('address');
            $table->timestamps();
			$table->softDeletes();
        });
		
		Schema::create('account_phone_num',function(Blueprint $table){
			$table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('phone_num_id');

            $table->foreign('account_id')->references('id')->on('accounts')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('phone_num_id')->references('id')->on('phone_numbers')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['account_id', 'phone_num_id']);
		});
		
		Schema::create('account_email',function(Blueprint $table){
			$table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('email_id');

            $table->foreign('account_id')->references('id')->on('accounts')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('email_id')->references('id')->on('emails')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['account_id', 'email_id']);
		});
		
		Schema::create('station_phone_num',function(Blueprint $table){
			$table->unsignedBigInteger('station_id');
            $table->unsignedBigInteger('phone_num_id');

            $table->foreign('station_id')->references('id')->on('stations')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('phone_num_id')->references('id')->on('phone_numbers')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['station_id', 'phone_num_id']);
		});
		
		Schema::create('station_email',function(Blueprint $table){
			$table->unsignedBigInteger('station_id');
            $table->unsignedBigInteger('email_id');

            $table->foreign('station_id')->references('id')->on('stations')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('email_id')->references('id')->on('emails')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['station_id', 'email_id']);
		});
		
		Schema::create('station_function',function(Blueprint $table){
			$table->unsignedBigInteger('station_id');
            $table->unsignedBigInteger('function_id');

            $table->foreign('station_id')->references('id')->on('stations')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('function_id')->references('id')->on('functions')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['station_id', 'function_id']);
		});
		
		Schema::create('supervisors', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->unsignedBigInteger('station_id');
			$table->unsignedBigInteger('user_id');
			$table->dateTime('from');
			$table->dateTime('to');
			$table->boolean('status');
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
        Schema::dropIfExists('supervisors');
        Schema::dropIfExists('station_function');
        Schema::dropIfExists('station_email');
        Schema::dropIfExists('station_phone_num');
        Schema::dropIfExists('account_email');
        Schema::dropIfExists('account_phone_num');
        Schema::dropIfExists('emails');
        Schema::dropIfExists('phone_numbers');
        Schema::dropIfExists('account_station');
        Schema::dropIfExists('stations');
        Schema::dropIfExists('accounts');
    }
}
