<?php

// app/Models/Role.php
namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Models\Permission;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Role extends SpatieRole
{

    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'guard_name', 'level']) // ដាក់ field ដែលចង់ Log
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Role has been {$eventName}");
    }

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