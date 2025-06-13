<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';
    protected $primaryKey = 'menu_id';

    protected $fillable = [
        'nama_menu',
        'deskripsi',
        'kategori_id',
        'ikan_id',
        'tipe_harga',
        'harga',
        'harga_per_100gr',
        'gambar_url'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'harga_per_100gr' => 'decimal:2',
    ];

    // Relationships
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'kategori_id');
    }

    public function ikan()
    {
        return $this->belongsTo(Ikan::class, 'ikan_id', 'ikan_id');
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'menu_id', 'menu_id');
    }

    public function hargaBeratTiers()
    {
        return $this->hasMany(HargaBeratTier::class, 'menu_id', 'menu_id');
    }
}
