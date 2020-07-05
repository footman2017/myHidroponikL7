<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $id_pengaliran
 * @property string $foto
 * @property Pengaliran $pengaliran
 */
class Kondisi extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'kondisi';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['id_pengaliran', 'foto'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pengaliran()
    {
        return $this->belongsTo('App\Pengaliran', 'id_pengaliran', 'id_pengaliran');
    }
}
