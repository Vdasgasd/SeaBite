<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    use HasFactory;

    protected $table = 'meja';
    protected $primaryKey = 'meja_id';
    public $timestamps = false;

    protected $fillable = [
        'nomor_meja',
        'kapasitas',
        'status'
    ];

    // Relationships
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'meja_id', 'meja_id');
    }

    public function reservasi()
    {
        return $this->hasMany(Reservasi::class, 'meja_id', 'meja_id');
    }
}
