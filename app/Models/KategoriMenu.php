<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriMenu extends Model
{
    use HasFactory;

    protected $table = 'kategori_menu';
    protected $primaryKey = 'kategori_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_kategori'
    ];

    // Relationships
    public function menus()
    {
        return $this->hasMany(Menu::class, 'kategori_id', 'kategori_id');
    }
}
