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

}
