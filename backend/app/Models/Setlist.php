<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setlist extends Model
{
    protected $fillable = [
        'user_id', 'title', 
    ];

    public function songs()
    {
        return $this->hasMany('App\Models\Song');
    }
    public function users()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
