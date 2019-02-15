<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

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

    public function getInitialsAttribute() {
        $buffer = explode(' ', $this->name);

        if (!empty($buffer[1])) {
            return strtoupper($buffer[0][0].$buffer[1][0]);
        } else {
            return strtoupper($buffer[0][0]);
        }
    }
}
