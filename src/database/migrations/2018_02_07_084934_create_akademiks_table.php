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
			$table->decimal('bahasa_indonesia');
			$table->decimal('bahasa_inggris');
			$table->decimal('matematika');
			$table->decimal('ipa');
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
