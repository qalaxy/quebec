<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('levels', function(Blueprint $table){
			$table->bigIncrements('id');
			$table->uuid('uuid');
			$table->string('name');
			$table->integer('order');
			$table->timestamps();
			$table->softDeletes();
		});
		
		if (!Schema::hasTable('users')) {
			Schema::create('users', function (Blueprint $table) {
				$table->bigIncrements('id');
				$table->uuid('uuid');
				$table->string('name');
				$table->string('email')->unique();
				$table->timestamp('email_verified_at')->nullable();
				$table->string('password');
				$table->boolean('status');
				$table->unsignedBigInteger('level_id');
				$table->rememberToken();
				$table->timestamps();
				$table->softDeletes();
				$table->foreign('level_id')->references('id')->on('levels')->onUpdate('cascade')->onDelete('cascade');
			});
		}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('levels');
    }
}
