<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $id_pengaliran
 * @property string $nama_foto
 * @property string $image_path
 * @property Pengaliran $pengaliran
 */
class Kondisi extends Model
{
    public $timestamps = false;
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
    protected $fillable = ['id', 'id_pengaliran', 'nama_foto', 'image_path'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pengaliran()
    {
        return $this->belongsTo('App\Pengaliran', 'id_pengaliran', 'id_pengaliran');
    }
}
