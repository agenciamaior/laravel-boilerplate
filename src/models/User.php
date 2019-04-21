<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    const ROLE_ADMIN = 0;
    const ROLE_COMMON = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAvatarAttribute() {
        if ($this->avatar_extension) {
            $image = sprintf('/files/users/%s.%s', $this->id, $this->avatar_extension);

            return '<img src="' . $image . '" class="img-circle elevation-2">';
        } else {
            $buffer = explode(' ', $this->name);
            $initials = '';
    
            if (!empty($buffer[1])) {
                $initials = strtoupper($buffer[0][0].$buffer[1][0]);
            } else {
                $initials = strtoupper($buffer[0][0]);
            }

            return '<div class="user-no-photo-avatar img-circle elevation-2">' . $initials . '</div>';
        }
    }

    public static function roles() {
        return [
            self::ROLE_ADMIN => 'Administrador',
            self::ROLE_COMMON => 'Comum',
        ];
    }

    public function getRoleStringAttribute() {
        return self::roles()[$this->role];
    }
}
