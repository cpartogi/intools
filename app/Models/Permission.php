<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $incrementing = true;
    protected $fillable = [
        'id', 'inherit_id', 'slug', 'description', 'name',
    ];
    public $table = 'permissions';
}
