<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
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
