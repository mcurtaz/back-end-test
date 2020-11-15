<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Name extends Model
{
    protected $table = 'node_tree_names';

    protected $fillable = [
        'idNode',
        'language',
        'name'
    ];

    public $timestamps = false;
}