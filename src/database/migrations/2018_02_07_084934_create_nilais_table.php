<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNilaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
	{
		Schema::create('nilais', function(Blueprint $table) {
			$table->increments('id');
			$table->string('nomor_un');
			$table->decimal('bobot')->nullable();
			$table->decimal('akademik')->nullable();
			$table->decimal('prestasi')->nullable();
			$table->decimal('zona')->nullable();
			$table->decimal('sktm')->nullable();
			$table->decimal('total')->nullable();
			$table->integer('user_id');
			$table->timestamps();
			$table->softDeletes();
		});
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
	public function down()
	{
		Schema::drop('nilais');
	}
}
