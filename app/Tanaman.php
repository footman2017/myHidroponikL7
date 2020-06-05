<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id_tanaman
 * @property string $email
 * @property string $nama_tanaman
 * @property string $tanggal_tanam
 * @property string $keterangan
 * @property float $min_ppm
 * @property float $max_ppm
 * @property integer $status
 * @property User $user
 * @property PembacaanSensor[] $pembacaanSensors
 * @property KondisiTanaman[] $kondisiTanamen
 */
class Tanaman extends Model
{
   public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tanaman';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id_tanaman';

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
    protected $fillable = ['id_tanaman' ,'email', 'nama_tanaman', 'tanggal_tanam', 'keterangan', 'min_ppm', 'max_ppm', 'status'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'email', 'email');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pembacaanSensors()
    {
        return $this->hasMany('App\PembacaanSensor', 'id_tanaman', 'id_tanaman');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kondisiTanamen()
    {
        return $this->hasMany('App\KondisiTanaman', 'id_tanaman', 'id_tanaman');
    }
}
