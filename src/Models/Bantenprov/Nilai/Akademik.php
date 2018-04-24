<?php

namespace Bantenprov\Nilai\Models\Bantenprov\Nilai;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Akademik extends Model
{
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'akademiks';
    protected $dates = [
        'deleted_at'
    ];
    protected $fillable = [
        'nomor_un',
        'bahasa_indonesia',
        'bahasa_inggris',
        'matematika',
        'ipa',
        'user_id',
    ];

    public function siswa()
    {
        return $this->belongsTo('Bantenprov\Siswa\Models\Bantenprov\Siswa\Siswa','nomor_un','nomor_un');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function calcNilaiBobot($request)
    {
        $bahasa_indonesia   = $request->input('bahasa_indonesia') * config('bantenprov.nilai.nilai.bahasa_indonesia');
        $bahasa_inggris     = $request->input('bahasa_inggris') * config('bantenprov.nilai.nilai.bahasa_inggris');
        $matematika         = $request->input('matematika') * config('bantenprov.nilai.nilai.matematika');
        $ipa                = $request->input('ipa') * config('bantenprov.nilai.nilai.ipa');

        return $bahasa_indonesia + $bahasa_inggris + $matematika + $ipa;
    }

    public function calcNilaiAkademik($request)
    {
        $bahasa_indonesia   = $request->input('bahasa_indonesia');
        $bahasa_inggris     = $request->input('bahasa_inggris');
        $matematika         = $request->input('matematika');
        $ipa                = $request->input('ipa');

        return $bahasa_indonesia + $bahasa_inggris + $matematika + $ipa;
    }
}
