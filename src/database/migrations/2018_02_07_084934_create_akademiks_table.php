<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAkademiksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
	{
		Schema::create('akademiks', function(Blueprint $table) {
			$table->increments('id');
			$table->string('nomor_un');
			$table->integer('bahasa_indonesia');
			$table->integer('bahasa_inggris');
			$table->integer('matematika');
			$table->integer('ipa');
			$table->integer('user_id')->nullable();
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
		Schema::drop('akademiks');
	}
}
