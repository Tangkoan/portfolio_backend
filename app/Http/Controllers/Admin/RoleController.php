<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission; // ហៅ Permission Model

class RoleController extends Controller
{
    public function index()
    {
        // ត្រូវទាញយក Permission ទាំងអស់ ដើម្បីយកទៅបង្ហាញក្នុង Modal
        $permissions = Permission::all();
        
        return view('admin.role.role_list', compact('permissions'));
    }

    public function fetchRoles(Request $request)
    {
        $query = Role::with('permissions'); // Eager load permissions សម្រាប់បង្ហាញក្នុង Table ផងដែរ

        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $roles = $query->latest()->paginate(10);
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles,name']);
        Role::create(['name' => $request->name]);
        return response()->json(['message' => 'Role created successfully!']);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|unique:roles,name,'.$id]);
        $role = Role::findOrFail($id);
        $role->update(['name' => $request->name]);
        return response()->json(['message' => 'Role updated successfully!']);
    }

    public function destroy($id)
    {
        Role::findOrFail($id)->delete();
        return response()->json(['message' => 'Role deleted successfully!']);
    }
}