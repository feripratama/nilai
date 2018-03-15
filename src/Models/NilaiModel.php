<?php namespace Bantenprov\Nilai\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * The NilaiModel class.
 *
 * @package Bantenprov\Nilai
 * @author  bantenprov <developer.bantenprov@gmail.com>
 */
class NilaiModel extends Model
{
    /**
    * Table name.
    *
    * @var string
    */
    protected $table = 'nilai';

    /**
    * The attributes that are mass assignable.
    *
    * @var mixed
    */
    protected $fillable = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
