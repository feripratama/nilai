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
        'label',
        'description',        
        'siswa_id'
    ];

    public function siswa()
    {
        return $this->belongsTo('Bantenprov\Siswa\Models\Bantenprov\Siswa\Siswa','siswa_id');
    }
}
