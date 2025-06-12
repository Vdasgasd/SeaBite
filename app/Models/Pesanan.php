<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';
    protected $primaryKey = 'pesanan_id';

    protected $fillable = [
        'meja_id',
        'waktu_pesanan',
        'status_pesanan',
        'total_harga'
    ];

    protected $casts = [
        'waktu_pesanan' => 'datetime',
        'total_harga' => 'decimal:2',
    ];

    // Relationships
    public function meja()
    {
        return $this->belongsTo(Meja::class, 'meja_id', 'meja_id');
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id', 'pesanan_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'pesanan_id', 'pesanan_id');
    }
}
