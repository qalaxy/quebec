<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\DB;

class EntrustSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        DB::beginTransaction();

        // Create table for storing roles
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
            $table->string('name');
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
			$table->boolean('global');
			$table->unsignedBigInteger('owner_id');
            $table->timestamps();
			$table->softDeletes();
			$table->foreign('owner_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
		
		

        // Create table for associating roles to users (Many-to-Many)
        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');
        });
		
		Schema::create('levels', function(Blueprint $table){
			$table->bigIncrements('id');
			$table->uuid('uuid');
			$table->string('name');
			$table->integer('order');
			$table->timestamps();
			$table->softDeletes();
		});
		

        // Create table for storing permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
            $table->string('name');
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
			$table->unsignedBigInteger('level_id');
            $table->timestamps();
			$table->softDeletes();
			$table->foreign('level_id')->references('id')->on('levels')->onUpdate('cascade')->onDelete('cascade');
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('permission_role', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('levels');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
    }
}
