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
        'siswa_id',
        'akademik_id',
        'prestasi_id',
        'zona_id',
        'sktm_id',
        'user_id'        
        
    ];

    public function siswa()
    {
        return $this->belongsTo('Bantenprov\Siswa\Models\Bantenprov\Siswa\Siswa','siswa_id');
    }
    public function prestasi()
    {
        return $this->belongsTo('Bantenprov\Prestasi\Models\Bantenprov\Prestasi\Prestasi','prestasi_id');
    }
    public function sktm()
    {
        return $this->belongsTo('Bantenprov\Sktm\Models\Bantenprov\Sktm\Sktm','sktm_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
