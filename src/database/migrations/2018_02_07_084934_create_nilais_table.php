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
			$table->integer('user_id');
			$table->bigInteger('nomor_un');
			$table->integer('siswa_id');
			$table->integer('akademik_id');
			$table->integer('prestasi_id');
			$table->string('zona_id');
			$table->integer('sktm_id');
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
