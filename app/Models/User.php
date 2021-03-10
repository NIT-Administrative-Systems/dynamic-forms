<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    const AUTH_TYPE_NETID = 'sso';
    const AUTH_TYPE_LOCAL = 'local';

    const AFF_STUDENT = 'student';
    const AFF_FACULTY = 'faculty';
    const AFF_STAFF = 'staff';

    /**
     * Never a primary affiliation; emeritus will be faculty instead.
     */
    const AFF_EMERITUS = 'emeritus';

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'full_name',
    ];

    public function getFullNameAttribute(): string
    {
        return sprintf('%s %s', $this->first_name, $this->last_name);
    }
}
