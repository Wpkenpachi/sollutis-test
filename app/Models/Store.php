<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'nome',
        'email'
    ];

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'loja_id', 'id');
    }
}
