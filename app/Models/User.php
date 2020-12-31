<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as authentictable;

class User extends authentictable
{
    use SoftDeletes;
    public $incrementing = true;
    protected $fillable = [
        'id', 'email', 'password', 'name', 'email_verified_at', 'is_active',
    ];
    protected $table = 'users';

    // protected $with = ['roles'];

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'role_user', 'user_id', 'role_id');
    }
}
