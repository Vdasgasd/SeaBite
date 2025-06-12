<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodeMasak extends Model
{
    use HasFactory;

    protected $table = 'metode_masak';
    protected $primaryKey = 'metode_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_metode',
        'biaya_tambahan'
    ];

    protected $casts = [
        'biaya_tambahan' => 'decimal:2',
    ];

    // Relationships
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'metode_masak_id', 'metode_id');
    }
}
