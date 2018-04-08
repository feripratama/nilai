<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBobotColumnOnNilaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
	{
        Schema::table('nilais', function (Blueprint $table) {
			$table->decimal('bobot')->nullable()->after('akademik');
		});
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
	public function down()
	{
        Schema::table('nilais', function (Blueprint $table) {
            $table->dropColumn('bobot');
        });
	}
}
