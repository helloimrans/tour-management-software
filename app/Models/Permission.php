<?php

namespace App\Models;

use Laratrust\Models\Permission as PermissionModel;

class Permission extends PermissionModel
{
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'group_name',
        'created_by',
        'updated_by'
    ];
}
