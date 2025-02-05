<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    // Explicitne určíme názov tabuľky
    protected $table = 'orders';

    // Nepoužívame štandardné Laravel timestampy, lebo máme vlastné stĺpce pre dátum a čas vytvorenia
    public $timestamps = false;

    // Ktoré atribúty môžeme hromadne priraďovať
    protected $fillable = [
        'user_id',
        'order_date',
        'order_time',
        'status',
        'notes',
        'order_type',
    ];

    // Prípadné pretypovania
    protected $casts = [
        'order_date' => 'date',
        // Pre order_time nie je natívny cast "time", takže môžeš ho spracovávať ako string alebo pomocou Carbon v accesoroch
    ];

    // Ak chceš pridať pomocnú metódu na formátovaný čas objednávky
    public function getFormattedOrderTimeAttribute()
    {
        return Carbon::parse($this->order_time)->format('H:i');
    }

    // Vzťah – objednávka patrí používateľovi
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
