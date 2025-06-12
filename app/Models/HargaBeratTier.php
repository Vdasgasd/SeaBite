<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaBeratTier extends Model
{
    use HasFactory;

    protected $table = 'harga_berat_tiers';

    protected $fillable = [
        'menu_id',
        'min_gram',
        'max_gram',
        'harga'
    ];

    protected $casts = [
        'min_gram' => 'decimal:2',
        'max_gram' => 'decimal:2',
        'harga' => 'decimal:2',
    ];

    // Relationships
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'menu_id');
    }
}
