<?php

namespace App\Models;

use Laratrust\Models\Role as RoleModel;

class Role extends RoleModel
{
    public $fillable = [
        'name',
        'display_name',
        'description',
    ];
}
