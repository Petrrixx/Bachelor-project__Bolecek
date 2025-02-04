<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * Názov tabuľky v databáze.
     *
     * @var string
     */
    protected $table = 'messages';

    /**
     * Primárny kľúč tabuľky.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Typ primárneho kľúča.
     *
     * @var string
     */
    protected $keyType = 'string'; // UUID

    /**
     * Indikuje, či má model časové značky (created_at, updated_at).
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Atribúty, ktoré je možné hromadne priradiť.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'surname',
        'phone',
        'email',
        'subject',
        'message',
        'attachment',
    ];

    /**
     * Atribúty, ktoré by mali byť skryté pri serializácii.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Atribúty, ktoré by mali byť prevedené na natívne typy.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
