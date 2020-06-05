<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id_tanaman
 * @property float $ppm1
 * @property float $ppm2
 * @property string $waktu
 * @property Tanaman $tanaman
 */
class PembacaanSensor extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'pembacaan_sensor';

    /**
     * @var array
     */
    protected $fillable = ['id_tanaman', 'ppm1', 'ppm2', 'waktu'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tanaman()
    {
        return $this->belongsTo('App\Tanaman', 'id_tanaman', 'id_tanaman');
    }
}
