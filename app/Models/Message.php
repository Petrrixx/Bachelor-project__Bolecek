<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $table = 'messages';

    protected $primaryKey = 'id';

    protected $keyType = 'string'; // UUID

    public $timestamps = false;

    protected $fillable = [
        'name',
        'surname',
        'phone',
        'email',
        'subject',
        'message',
        'attachment',
    ];

    protected $hidden = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
