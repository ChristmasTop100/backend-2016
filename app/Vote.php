<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'song_id', 'score',
    ];

  public function users() {
    return $this->hasOne('App\User', 'id', 'user_id');
  }

  public function songs() {
    return $this->hasOne('App\Song', 'id', 'song_id');
  }
}
