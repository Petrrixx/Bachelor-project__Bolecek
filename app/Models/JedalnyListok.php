<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JedalnyListok extends Model
{
    use HasFactory;

    // Explicitne určíme názov tabuľky
    protected $table = 'jedalny_listok';

    // Nepoužívame štandardné timestampy
    public $timestamps = false;

    // Hromadné priraďovanie
    protected $fillable = [
        'food_name',
        'priloha_name',
        'obloha_name',
        'all_allergens',
        'hmotnost',
        'cena',
    ];
}
