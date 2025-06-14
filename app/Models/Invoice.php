<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoice';
    protected $primaryKey = 'invoice_id';

    protected $fillable = [
        'pesanan_id',
        'kasir_id',
        'waktu_pembayaran',
        'metode_pembayaran',
        'total_bayar',
        'snap_token',
        'status_pembayaran'
    ];

    protected $casts = [
        'waktu_pembayaran' => 'datetime',
        'total_bayar' => 'decimal:2',
    ];

    // Relationships
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id', 'pesanan_id');
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id', 'id');
    }
}
