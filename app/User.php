<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function designation()
    {
        return $this->belongsTo('App\Models\Designation');
    }

    public function division()
    {
        return $this->belongsTo('App\Models\Division');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\District');
    }

    public function upazila()
    {
        return $this->belongsTo('App\Models\Upazila');
    }

    public function union()
    {
        return $this->belongsTo('App\Models\Union');
    }
}
