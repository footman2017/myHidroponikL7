<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id_tanaman
 * @property string $deskripsi
 * @property float $total_serapan_nutrisi
 * @property string $tanggal_awal
 * @property string $tanggal_akhir
 * @property Tanaman $tanaman
 */
class KondisiTanaman extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'kondisi_tanaman';

    /**
     * @var array
     */
    protected $fillable = ['id_tanaman', 'deskripsi', 'total_serapan_nutrisi', 'tanggal_awal', 'tanggal_akhir'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tanaman()
    {
        return $this->belongsTo('App\Tanaman', 'id_tanaman', 'id_tanaman');
    }
}
