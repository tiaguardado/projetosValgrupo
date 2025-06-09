<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encomendas extends Model
{
    /** @use HasFactory<\Database\Factories\EncomendasFactory> */
    use HasFactory;

    protected $fillable = [
        'tamanhoPizza',
        'basePizza',
        'ingredientesExtra',
        'estadoPedido',

    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
