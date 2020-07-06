<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id_pengaliran
 * @property float $ppm1
 * @property float $ppm2
 * @property string $waktu
 * @property Pengaliran $pengaliran
 */
class PembacaanSensor extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'waktu';
    protected $keyType = 'string';
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'pembacaan_sensor';

    /**
     * @var array
     */
    protected $fillable = ['id_pengaliran', 'ppm1', 'ppm2', 'waktu'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pengaliran()
    {
        return $this->belongsTo('App\Pengaliran', 'id_pengaliran', 'id_pengaliran');
    }
}
