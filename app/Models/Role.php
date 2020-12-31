<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    // use SoftDeletes;
    public $incrementing = true;
    protected $fillable = [
        'id', 'name', 'slug', 'description',
    ];
    protected $table = 'roles';

    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission', 'permission_role', 'role_id', 'permission_id');
    }
}
