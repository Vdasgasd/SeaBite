<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ikan extends Model
{
    use HasFactory;

    protected $table = 'ikan';
    protected $primaryKey = 'ikan_id';

    protected $fillable = [
        'nama_ikan',
        'stok_gram'
    ];

    protected $casts = [
        'stok_gram' => 'decimal:2',
    ];

    // Relationships
    public function menus()
    {
        return $this->hasMany(Menu::class, 'ikan_id', 'ikan_id');
    }
}
