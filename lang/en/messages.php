<?php

return [

    // Form Delete
        'confirm_delete_title'   => 'Do you want to delete this data?',
        'confirm_delete_message' => 'This action cannot be recovered!!!',
        'btn_confirm'            => 'Yes, Confirm!',
        'btn_cancel'             => 'Cancel',
    // End Form Delete

    'welcome' => 'Welcome to our application',
    'dashboard' => 'Dashboard',
    'profile' => 'User Info',
    'change_password' => 'Change Password',
    'logout' => 'Logout',

    // User Management
    'user_management' => 'User Management',
    'new_password_optional' => 'New Password (Optional)',
    'finish'=> 'Finish',
    'skip_this_user' => 'Skip this user',
    'no_users_found_matching_your_search'=>'No users found matching your search.',
    'columns' => 'Columns',
    'search' => 'search...',
    'search_placeholder' => 'Search users...', // បន្ថែមថ្មី
    'add_user' => 'Add User',
    'user' => 'User',
    'role' => 'Role',
    'email'=> 'Email',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
    'actions' => 'Actions',
    'show' => 'Show',
    'selected_items' => 'selected', // បន្ថែមថ្មី
    'edit_sequence' => 'Edit Sequence', // បន្ថែមថ្មី
    'delete_selected' => 'Delete Selected', // បន្ថែមថ្មី
    'of' => 'of', // បន្ថែមថ្មី (សម្រាប់ Edit 1 of 5)
    'save_and_next' => 'Save & Next', // បន្ថែមថ្មី

    // Alerts & Confirmations (បន្ថែមថ្មីសម្រាប់ JS)
    'confirm_stop_sequence' => 'Stop editing sequence?',
    'select_users_first' => 'Please select users first.',
    'all_users_updated' => 'All selected users updated!',
    'fix_errors' => 'Please fix the errors below.',
    'something_wrong' => 'Something went wrong!',
    'network_error' => 'Network Error',
    'delete_success' => 'Deleted successfully.',
    'delete_fail' => 'Failed to delete.',

    'create_new_user' => 'Create New User',
    'edit_user' => 'Edit User',
    'full_name'=> 'Full Name',
    'email_address'=>'Email Address',
    'assign_role'=> 'Assign Role',
    'password'=> 'Password',
    'select_a_role' => 'Select a role',
    'save'=> 'Save',
    'update'=> 'Update',
    'cancel'=> 'Cancel',

    // Controller Messages
    'user_created'           => 'User created successfully.',
    'user_updated'           => 'User updated successfully.',
    'user_deleted'           => 'User deleted successfully.',
    'email_duplicate'        => 'This email is already registered.',
    'role_permission_denied' => 'You do not have permission to assign this role.',
    'unauthorized_action'    => 'Unauthorized! You cannot modify a Super Admin account.',
    'field_required'         => 'This field is required.',
    'password_min'           => 'Password must be at least :min characters.',
    'invalid_data'           => 'Invalid data provided.',
    'bulk_delete_success'    => ':count users deleted successfully.',
    'bulk_update_success'    => 'Roles updated for :count users successfully.',

    // Role & Permission Page
    'role_management'=> 'Role Management',
    'add_role' => 'Add Role',
    'edit_role' => 'Edit Role',
    'role_name' => 'Role Name',
    'level' => 'Level',
    'create_new_role' => 'Create New Role',
    'please_enter' => 'Please Enter',

    // Permission
    'permission_management' => 'Permission Management',
    'add_permission' => 'Add Permission',
    'edit_permission' => 'Edit Permission',
    'permission_name'=> 'Permission Name',
    'guard_name'=> 'Guard Name',
    'create_permission' => 'Create Permission',

    // Rule Page
    'assignment_rules' => 'Assignment Rules',
    'assignable_permission' => 'Assignable Permission', // កែពាក្យខុស permission
    'configure' => 'Configure',
    'configure_rules'=> 'Configure Rules',

    // Action Page
    'activity_logs' => 'Activity Logs',
    'action' => 'Action',
    'subject' => 'Subject',
    'change' => 'Change',
    'date' => 'Date',

    // Theme Page
    'theme_customizer' => 'Theme Customizer',
    'light'=>'Light',
    'dark' => 'Dark',

    // General
    'role_management' => 'Role Management',
    'search_placeholder' => 'Search roles...',
    'add_role' => 'Add Role',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'selected' => 'selected',
    'columns' => 'Columns',
    'cancel' => 'Cancel',
    'save' => 'Save',
    'update' => 'Update',
    'save_next' => 'Save & Next',
    'finish' => 'Finish',
    'skip' => 'Skip',
    'loading' => 'Loading...',
    'actions' => 'Actions',
    
    // Table Headers
    'th_role_name' => 'Role Name',
    'th_level' => 'Level',
    'th_permissions' => 'Permissions Preview',
    'th_users' => 'Users',
    'no_roles_found' => 'No roles found.',
    'no_permissions_assigned' => 'No permissions assigned',
    'more' => 'more',

    // Forms & Inputs
    'role_name' => 'Role Name',
    'role_level' => 'Role Level (Priority)',
    'level_hint_1' => '(Higher = More Power)',
    'level_hint_2' => 'Example: User=10, Manager=30, Admin=50.',
    'create_new_role' => 'Create New Role',
    'edit_role' => 'Edit Role',
    'editing_sequence' => 'Editing role :current of :total',
    
    // Permissions Modal
    'assign_permissions' => 'Assign Permissions',
    'select_permissions' => 'Select Permissions',
    'select_all' => 'Select All',
    'uncheck_all' => 'Uncheck All',
    'no_assignable_permissions' => 'No assignable permissions found.',
    'save_permissions' => 'Save Permissions',
    'saving' => 'Saving...',
    
    // Confirmation Modal
    'confirm_delete_title' => 'Do you want to delete this data?',
    'confirm_delete_msg' => 'This action cannot be recovered!!!',
    'btn_yes_confirm' => 'Yes, Confirm!',
    'stop_editing_sequence' => 'Stop editing sequence?',
    
    // Controller Messages (Success/Error)
    'success_create' => 'Role created successfully!',
    'success_update' => 'Role updated successfully!',
    'success_delete' => 'Role deleted successfully!',
    'success_bulk_delete' => 'Selected roles deleted successfully!',
    'success_permission_assign' => 'Permissions assigned successfully!',
    
    'error_unauthorized_edit' => 'Unauthorized: You cannot edit a role with a higher level than yours.',
    'error_unauthorized_delete' => 'Unauthorized: You cannot delete a role with a higher level than yours.',
    'error_has_users' => 'Cannot delete role ":name" because it has :count users assigned.',
    'error_bulk_has_users' => 'Cannot delete selected roles. The following roles have users: :names.',
    'error_level_max' => 'You cannot create a role with a level higher than your own (:level).',
    'error_generic' => 'Error occurred!',
    'network_error' => 'Network error. Please try again.',
    
    'error_prefix' => 'Error: ',
    'security_warning_unauthorized' => 'Security Warning: You tried to assign unauthorized permissions.',
    'success_permission_update' => 'Permissions updated successfully!',
    'server_error' => 'Server Error',
];