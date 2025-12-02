<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    // 1. បង្ហាញទំព័រ Permission List
    public function index()
    {
        return view('admin.permission.permission_list');
    }

    // 2. AJAX Fetch Data
    public function fetchPermissions(Request $request)
    {
        $query = Permission::query();

        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $permissions = $query->latest()->paginate(10); // បង្ហាញ 10 ក្នុងមួយទំព័រ

        return response()->json($permissions);
    }

    // 3. Create Permission
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name'
        ]);

        // បង្កើត Permission (Guard name នឹងយក web ដោយស្វ័យប្រវត្តិ)
        Permission::create(['name' => $request->name]);

        return response()->json(['message' => 'Permission created successfully!']);
    }

    // 4. Update Permission
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,'.$id
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update(['name' => $request->name]);

        return response()->json(['message' => 'Permission updated successfully!']);
    }

    // 5. Delete Permission
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json(['message' => 'Permission deleted successfully!']);
    }
}