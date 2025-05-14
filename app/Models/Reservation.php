<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    // Vypnutie 'updated_at'
    const UPDATED_AT = null;

    // Zachovanie 'created_at'
    public $timestamps = true;

    protected $fillable = [
        'name',
        'email',
        'user_contact',
        'date',
        'time',
        'guests',
        'accept',
    ];

    protected $casts = [
        'accept' => 'boolean',
        'date' => 'date',
    ];

    public function getFormattedTimeAttribute()
    {
        return \Carbon\Carbon::createFromFormat('H:i:s', $this->time)->format('H:i');
    }
}
