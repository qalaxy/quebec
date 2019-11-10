<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateStaticDataTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		DB::beginTransaction();
		
        Schema::create('error_status', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->string('name');
			$table->string('code');
			$table->string('description')->nullable();
            $table->timestamps();
			$table->softDeletes();
        });
		
		Schema::create('systems', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->string('name');
			$table->string('description')->nullable();
            $table->timestamps();
			$table->softDeletes();
        });
		
		Schema::create('offices', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->string('name');
			$table->string('description')->nullable();
            $table->timestamps();
			$table->softDeletes();
        });
		
		Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->string('name');
			$table->string('description')->nullable();
            $table->timestamps();
			$table->softDeletes();
        });
		
		Schema::create('functions', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->string('name');
			$table->string('description')->nullable();
            $table->timestamps();
			$table->softDeletes();
        });
		
		Schema::create('function_product',function(Blueprint $table){
			$table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('function_id');

            $table->foreign('product_id')->references('id')->on('products')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('function_id')->references('id')->on('functions')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['product_id', 'function_id']);
		});
		
		Schema::create('trackers', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->uuid('uuid');
			$table->unsignedBigInteger('user_id');
			$table->string('action');
			$table->date('date');
			$table->time('time');
            $table->timestamps();
			$table->softDeletes();
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
        Schema::dropIfExists('trackers');
        Schema::dropIfExists('function_product');
        Schema::dropIfExists('functions');
        Schema::dropIfExists('products');
        Schema::dropIfExists('offices');
        Schema::dropIfExists('systems');
        Schema::dropIfExists('error_status');
    }
}
