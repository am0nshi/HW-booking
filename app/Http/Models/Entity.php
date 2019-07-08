<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
}
