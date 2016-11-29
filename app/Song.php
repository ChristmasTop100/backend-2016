<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'artist', 'url', 'image',
    ];

    public $timestamps = false;

    public function votes() {
        return $this->hasMany('App\Vote');
    }
}
