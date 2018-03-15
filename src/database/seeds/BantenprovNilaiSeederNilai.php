<?php

/* Require */
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

/* Models */
use Bantenprov\Nilai\Models\Bantenprov\Nilai\Nilai;

class BantenprovNilaiSeederNilai extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
	public function run()
	{
        Model::unguard();

        $nilais = (object) [
            (object) [
                'siswa_id' => '1',
                'label' => 'Nilai 1',
                'description' => 'Nilai satu'
            ],
            (object) [
                'siswa_id' => '2',
                'label' => 'Nilai 2',
                'description' => 'Nilai dua',
            ]
        ];

        foreach ($nilais as $nilai) {
            $model = Nilai::updateOrCreate(
                [
                    'siswa_id' => $nilai->siswa_id,
                ],
                [
                    'label' => $nilai->label,
                ],
                [
                    'description' => $nilai->description,
                ]
            );
            $model->save();
        }
	}
}
