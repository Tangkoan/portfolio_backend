<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Spatie\Permission\Models\Role;
use App\Models\Role; // <--- ដាក់អាថ្មីរបស់អ្នកចូល
// use Spatie\Permission\Models\Permission;
use App\Models\Permission; // <--- ដាក់អាថ្មីរបស់អ្នកចូល
use Illuminate\Support\Facades\Auth; // <--- ត្រូវប្រាកដថាមាន (សំខាន់ណាស់)

use Illuminate\Support\Facades\Log;      // ៣. សម្រាប់មើល Log បើមាន Error

class AssignPermissionController extends Controller
{
    // 1. បង្ហាញផ្ទាំង (បង្ហាញតែ Checkbox ដែលអនុញ្ញាត)
    public function index()
    {
        // មុខងារនេះលែងប្រើហើយ ព្រោះយើងប្រើ Modal លើ Role List តែម្តង
        return abort(404);
    }

    /**
     * Logic កំណត់ថាអ្នកណាអាចផ្ដល់សិទ្ធិអ្វីបានខ្លះ
     */
    private function getAssignablePermissions($user)
    {
        if ($user->hasRole('Super Admin')) {
            return Permission::all()->pluck('name')->toArray();
        }

        // យក Role ដំបូងរបស់ User
        $userRole = $user->roles->first(); 

        if ($userRole) {
            // បច្ចេកទេសការពារ៖ Re-query ដោយប្រើ Model របស់យើង 
            // ដើម្បីធានាថាវាស្គាល់ Relationship 'assignablePermissions'
            $roleWithRelation = Role::find($userRole->id);
            
            if ($roleWithRelation) {
                return $roleWithRelation->assignablePermissions->pluck('name')->toArray();
            }
        }

        return [];
    }

    /**
     * API: ទាញយក Data ទៅបង្ហាញក្នុង Modal
     */
    public function fetchRolePermissions($roleId)
    {
        try {
            $role = Role::findById($roleId); // ឬ Role::findOrFail($roleId) ប្រសិនបើប្រើ custom model ខ្លះ
            $currentUser = Auth::user();

            // ១. យកបញ្ជីសិទ្ធិដែល User បច្ចុប្បន្ន (Admin) អាចមើលឃើញ/អាចចែកចាយបាន
            // (លទ្ធផលនឹងចេញតែ user-create, user-delete... អត់មាន user-edit ទេ)
            $allowedPermissionNames = $this->getAssignablePermissions($currentUser);
            
            // Query យក object ពេញលេញដើម្បីបង្ហាញឈ្មោះក្នុង Checkbox
            $availablePermissions = Permission::whereIn('name', $allowedPermissionNames)->get();

            // ២. ពិនិត្យមើលថា Role នោះមានសិទ្ធិអ្វីខ្លះហើយ (ដើម្បី Tick លើ Checkbox)
            // យកតែសិទ្ធិណាដែលស្ថិតក្នុង List ដែល Admin មើលឃើញប៉ុណ្ណោះ
            $checkedPermissions = $role->permissions
                                       ->whereIn('name', $allowedPermissionNames)
                                       ->pluck('name');

            return response()->json([
                'available_permissions' => $availablePermissions, // សម្រាប់ Loop បង្កើត Checkbox
                'checked_permissions' => $checkedPermissions      // សម្រាប់ Tick ប្រអប់
            ]);

        } catch (\Exception $e) {
            // Logic នៅដដែល គ្រាន់តែប្តូរ Message
            return response()->json(['message' => __('messages.error_prefix') . $e->getMessage()], 500);
        }
    }

    /**
     * API: Save Permissions (Logic ការពារសិទ្ធិដែលមើលមិនឃើញ)
     */
    public function update(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'present|array' // array ឈ្មោះ permission ដែលបាន tick
        ]);

        try {
            $role = Role::findById($request->role_id);
            $currentUser = Auth::user();

            // ១. យកបញ្ជីសិទ្ធិដែល User នេះមានសិទ្ធិគ្រប់គ្រង (Scope)
            $manageablePermissions = $this->getAssignablePermissions($currentUser);
            

            // ២. Validate: ការពារកុំអោយគេ Hack បញ្ជូន permission ដែលគេគ្មានសិទ្ធិគ្រប់គ្រងមក
            // បើ $request->permissions មានសិទ្ធិណាដែលមិនស្ថិតក្នុង $manageablePermissions -> Error
            $submittedPermissions = $request->permissions;
            $illegalPermissions = array_diff($submittedPermissions, $manageablePermissions);

            if (!empty($illegalPermissions)) {
                return response()->json(['message' => __('messages.security_warning_unauthorized')], 403);
            }

            // ៣. Logic រក្សាសិទ្ធិចាស់ (Preserve Invisible Permissions)
            // ឧទាហរណ៍: Role មាន 'user-edit' តែ Admin មើលអត់ឃើញ។ បើយើង Sync ភ្លាម 'user-edit' នឹងបាត់។
            
            // ក. យកសិទ្ធិទាំងអស់ដែល Role មានបច្ចុប្បន្ន
            $currentRolePermissions = $role->permissions->pluck('name')->toArray();

            // ខ. ទុកសិទ្ធិណាដែល Admin មើលមិនឃើញ (កុំអោយប៉ះពាល់)
            $keepPermissions = array_diff($currentRolePermissions, $manageablePermissions);

            // គ. បូកបញ្ចូលគ្នា: (សិទ្ធិដែលត្រូវរក្សាទុក) + (សិទ្ធិថ្មីដែល Admin បាន Tick)
            $finalPermissions = array_merge($keepPermissions, $submittedPermissions);

            // ឃ. Save ចូល Database
            $role->syncPermissions($finalPermissions);

            return response()->json(['message' => __('messages.success_permission_update')]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => __('messages.server_error')], 500);
        }
    }
}