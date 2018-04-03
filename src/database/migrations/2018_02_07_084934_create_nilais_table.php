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
			$table->Integer('siswa_id');
			$table->decimal('akademik');
			$table->decimal('prestasi');
			$table->decimal('zona');
			$table->decimal('sktm');
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
