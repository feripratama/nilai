<?php

namespace Bantenprov\Nilai\Models\Bantenprov\Nilai;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nilai extends Model
{
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'nilais';
    protected $dates = [
        'deleted_at'
    ];
    protected $fillable = [
        'nomor_un',
        'kegiatan_id',
        'bobot',
        'akademik',
        'prestasi',
        'zona',
        'sktm',
        'total',
        'user_id',
    ];

    public function setTotalAttribute($value)
    {
        $nilai[]    = $this->akademik;
        $nilai[]    = $this->prestasi;
        $nilai[]    = $this->zona;
        $nilai[]    = $this->sktm;

        $this->attributes['total'] = array_sum($nilai);
    }

    public function siswa()
    {
        return $this->belongsTo('Bantenprov\Siswa\Models\Bantenprov\Siswa\Siswa','nomor_un','nomor_un');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function nilai_akademik()
    {
        return $this->belongsTo('Bantenprov\Nilai\Models\Bantenprov\Nilai\Akademik','nomor_un','nomor_un');
    }

    public function kegiatan()
    {
        return $this->belongsTo('Bantenprov\Kegiatan\Models\Bantenprov\Kegiatan','kegiatan_id');
    }
}
