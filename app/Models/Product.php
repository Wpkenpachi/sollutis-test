<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'nome',
        'valor',
        'loja_id',
        'ativo'
    ];

    public function getValorAttribute($value) {
        $divided = $value / 100;
        return "R$ " . number_format($divided, 2, ',', '.');
    }
}
