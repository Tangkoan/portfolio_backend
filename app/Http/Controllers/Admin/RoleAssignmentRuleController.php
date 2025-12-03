<?php

// app/Http/Controllers/Admin/RoleAssignmentRuleController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role; 
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleAssignmentRuleController extends Controller
{
    // 1. បង្ហាញតារាង Role (admin.rules.index)
    public function index()
    {
        // ទាញយក Role ទាំងអស់ លើកលែងតែ Super Admin
        // withCount('assignablePermissions') ដើម្បីបង្ហាញថា Role ហ្នឹងមានសិទ្ធិចែកចាយប៉ុន្មាន
        $roles = Role::where('name', '!=', 'Super Admin')
                     ->withCount('assignablePermissions') 
                     ->get();

        return view('admin.rules.index', compact('roles'));
    }

    // 2. បង្ហាញ Form កំណត់សិទ្ធិ (admin.rules.edit)
    public function edit($id)
    {
        $role = Role::with('assignablePermissions')->findOrFail($id);
        
        // Group permission តាមឈ្មោះខាងមុខ (ឧ. user-create, product-create -> Group 'User', 'Product')
        // ដើម្បីងាយស្រួលមើលពេល Tick
        $permissions = Permission::all()->groupBy(function($data) {
            return explode('-', $data->name)[0]; // យកពាក្យដំបូងធ្វើជា Group Name
        });

        return view('admin.rules.edit', compact('role', 'permissions'));
    }

    // 3. Save ទិន្នន័យ (admin.rules.update)
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        
        // Sync ចូលក្នុង Table 'role_assignable_permissions'
        $role->assignablePermissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.rules.index')
                         ->with('success', "Rules for '{$role->name}' updated successfully!");
    }
}