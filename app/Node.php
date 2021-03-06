<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $table = 'node_tree';

    protected $fillable = [
        'level',
        'iLeft',
        'iRight'
    ];

    public $timestamps = false;

    protected $primaryKey = 'idNode'; // cambia la chiave primaria (di default laravel si aspetta si chiami 'id')

}
