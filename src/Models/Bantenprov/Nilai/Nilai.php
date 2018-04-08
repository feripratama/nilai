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
        'user_id',
        'siswa_id',
        'bobot',
        'akademik',
        'prestasi',
        'zona',
        'sktm'

    ];

    public function siswa()
    {
        return $this->belongsTo('Bantenprov\Siswa\Models\Bantenprov\Siswa\Siswa','siswa_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
