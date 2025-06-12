<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    use HasFactory;

    protected $table = 'detail_pesanan';
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    protected $fillable = [
        'pesanan_id',
        'menu_id',
        'metode_masak_id',
        'jumlah',
        'berat_gram',
        'catatan',
        'subtotal'
    ];

    protected $casts = [
        'berat_gram' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relationships
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id', 'pesanan_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'menu_id');
    }

    public function metodeMasak()
    {
        return $this->belongsTo(MetodeMasak::class, 'metode_masak_id', 'metode_id');
    }
}
