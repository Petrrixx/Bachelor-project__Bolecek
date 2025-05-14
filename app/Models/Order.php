<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    // Nepoužívame defaultné timestamps, máme vlastné stĺpce
    public $timestamps = false;

    // Explicitný názov tabuľky
    protected $table = 'orders';

    // Hromadné priraďovanie
    protected $fillable = [
        'user_fullname',
        'user_email',
        'user_contact',
        'order_date',
        'order_time',
        'order_type',
        'delivery_address',
        'notes',
        'status',
        'created_date',
        'created_time',
    ];

    // Casty pre dátumy
    protected $casts = [
        'order_date'   => 'date',
        'created_date' => 'date',
    ];

    /**
     * Pomocný accessor na formátovaný čas objednávky (HH:mm)
     */
    public function getFormattedOrderTimeAttribute(): string
    {
        return Carbon::parse($this->order_time)->format('H:i');
    }
}
