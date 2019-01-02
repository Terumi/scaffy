<?php

namespace App;


class User extends Model
{
    protected $table = 'user';
    protected $fillable = [];
    protected $guarded = [];
    protected $hidden = [];

    public function UserType(){return $this->hasOne('App\UserType');}
}
