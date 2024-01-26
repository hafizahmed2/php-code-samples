<?php

namespace App;


use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Bloger extends Authenticatable
{
	use HasApiTokens,Notifiable;
	
	protected $guard = 'bloger-api';

	protected $fillable = [
        'name', 'email', 'password','role'
    ];

     
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function blog()
    {
        return $this->hasMany('App\Blog');
    }
}
