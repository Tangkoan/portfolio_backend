<?php

// app/Models/Role.php
namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Models\Permission;

class Role extends SpatieRole
{
    // Relationship: តើ Role នេះអាចចែកចាយ Permission អ្វីខ្លះ?
    public function assignablePermissions()
    {
        return $this->belongsToMany(
            Permission::class, 
            'role_assignable_permissions', // Table ថ្មីរបស់យើង
            'role_id', 
            'permission_id'
        );
    }
}