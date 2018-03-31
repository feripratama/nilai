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
        'user_id'        
        
    ];
    
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
