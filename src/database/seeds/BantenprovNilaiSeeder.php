<?php

use Illuminate\Database\Seeder;

class BantenprovNilaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BantenprovNilaiSeederAkademik::class);
        $this->call(BantenprovNilaiSeederNilai::class);
    }
}
