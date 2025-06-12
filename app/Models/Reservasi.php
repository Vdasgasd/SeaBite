<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    protected $table = 'reservasi';
    protected $primaryKey = 'reservasi_id';
    public $timestamps = false;

    protected $fillable = [
        'meja_id',
        'nama_pelanggan',
        'telepon',
        'waktu_reservasi',
        'jumlah_tamu',
        'status'
    ];

    protected $casts = [
        'waktu_reservasi' => 'datetime',
    ];

    // Relationships
    public function meja()
    {
        return $this->belongsTo(Meja::class, 'meja_id', 'meja_id');
    }
}
