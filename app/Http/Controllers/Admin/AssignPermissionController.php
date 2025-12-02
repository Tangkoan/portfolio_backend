<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Support\Facades\Log;      // ៣. សម្រាប់មើល Log បើមាន Error

class AssignPermissionController extends Controller
{
    // 1. បង្ហាញផ្ទាំង (បង្ហាញតែ Checkbox ដែលអនុញ្ញាត)
    public function index()
    {
        // មុខងារនេះលែងប្រើហើយ ព្រោះយើងប្រើ Modal លើ Role List តែម្តង
        return abort(404);
    }

    // 2. ទាញយក Permission ដែល Role នោះមានស្រាប់ (ពេលរើស Role)
   // API: ទាញយក Permission ដែល Role មានស្រាប់
    public function fetchRolePermissions($roleId)
    {
        try {
            $role = Role::findById($roleId);
            // បញ្ជូនត្រលប់ជា Array នៃឈ្មោះ Permission (String)
            return response()->json($role->permissions->pluck('name'));
        } catch (\Exception $e) {
            return response()->json([], 200); // បើ Role រកមិនឃើញ បញ្ជូន array ទទេ
        }
    }

    // API: Save Permissions (កន្លែងដែលចេញ Error 500)
    public function update(Request $request)
    {
        // ១. Validation
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'present|array' // present: ត្រូវតែមាន key នេះ ទោះវាជា array ទទេក៏ដោយ
        ]);

        try {
            // ២. រក Role
            $role = Role::findById($request->role_id);

            // ៣. Sync Permissions (លុបចាស់ ដាក់ថ្មី)
            // សំខាន់៖ Spatie ត្រូវការឈ្មោះ Permission (String) ក្នុង Array
            $role->syncPermissions($request->permissions);

            return response()->json(['message' => 'Permissions assigned successfully!']);

        } catch (\Exception $e) {
            // បើមាន Error, កត់ត្រាចូល Log ហើយប្រាប់ទៅ JS វិញ
            Log::error($e->getMessage());
            return response()->json(['message' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * កំណត់សិទ្ធិដែល Admin អាចចែកឱ្យគេបាន
     */
    private function getAssignablePermissions($user)
    {
        if ($user->hasRole('Super Admin')) {
            // Super Admin: អាចចែកបានគ្រប់សិទ្ធិ
            return Permission::all()->pluck('name')->toArray();
        } 
        
        if ($user->hasRole('Admin')) {
            // Admin: មានសិទ្ធិប្រើច្រើន តែចែកឱ្យគេបានតែ ២ នេះទេ
            return [
                'role-delete',
                'system-setting'
            ];
        }

        return []; // Role ផ្សេងទៀតចែកអីមិនបានទេ
    }

    
}