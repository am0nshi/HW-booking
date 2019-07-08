<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class EntityBooking extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'from', 'to', 'user_id', 'entity_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'from' => 'datetime',
        'to' => 'datetime',
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
