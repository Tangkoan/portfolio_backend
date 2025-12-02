@extends('admin.dashboard')

@section('content')
<div class="w-full h-full px-6 py-5" 
     x-data="roleManagement()" 
     x-init="fetchRoles()">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-text-color flex items-center gap-2">
                <i class="ri-shield-user-line text-primary"></i> Role Management
            </h1>
            <p class="text-sm text-secondary mt-1">Create roles and assign permissions via popup.</p>
        </div>

        <div class="flex gap-3 w-full md:w-auto">
            <div class="relative w-full md:w-64">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-secondary">
                    <i class="ri-search-line"></i>
                </span>
                <input type="text" x-model="search" @keyup.debounce.500ms="fetchRoles()"
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-input-border bg-card-bg text-text-color focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder-secondary"
                       placeholder="Search roles...">
            </div>

            <button @click="openModal('create')" 
                    class="bg-primary hover:opacity-90 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-primary/30 transition-all flex items-center gap-2">
                <i class="ri-add-circle-line"></i> <span class="hidden sm:inline">Add Role</span>
            </button>
        </div>
    </div>

    <div class="bg-card-bg rounded-xl shadow-custom border border-border-color overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-page-bg/50 border-b border-border-color text-text-color text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold w-1/4">Role Name</th>
                        <th class="px-6 py-4 font-bold">Permissions Preview</th>
                        <th class="px-6 py-4 font-bold text-right w-40">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-color">
                    <template x-for="role in roles" :key="role.id">
                        <tr class="hover:bg-page-bg/30 transition-colors group">
                            <td class="px-6 py-4 align-top">
                                <span class="font-bold text-text-color text-lg" x-text="role.name"></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <template x-if="role.permissions && role.permissions.length > 0">
                                        <div class="flex flex-wrap gap-2">
                                            <template x-for="perm in role.permissions.slice(0, 5)" :key="perm.id">
                                                <span class="px-2 py-1 rounded text-xs font-medium bg-input-bg border border-input-border text-secondary select-none" 
                                                      x-text="perm.name.replace(/-/g, ' ')"></span>
                                            </template>
                                            <span x-show="role.permissions.length > 5" class="text-xs text-secondary px-1 self-center">
                                                +<span x-text="role.permissions.length - 5"></span> more
                                            </span>
                                        </div>
                                    </template>
                                    
                                    <span x-show="!role.permissions || role.permissions.length === 0" class="text-xs text-secondary italic opacity-50">
                                        No permissions assigned
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right align-top">
                                <div class="flex justify-end gap-2">
                                    <button @click="openPermissionModal(role)" 
                                            class="h-8 w-8 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 hover:bg-yellow-100 transition-colors flex items-center justify-center"
                                            title="Assign Permissions">
                                        <i class="ri-shield-keyhole-line"></i>
                                    </button>

                                    <button @click="openModal('edit', role)" class="h-8 w-8 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 hover:bg-blue-100 transition-colors flex items-center justify-center">
                                        <i class="ri-pencil-line"></i>
                                    </button>

                                    <button @click="confirmDelete(role.id)" class="h-8 w-8 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 hover:bg-red-100 transition-colors flex items-center justify-center">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="roles.length === 0">
                        <td colspan="3" class="px-6 py-12 text-center text-secondary">No roles found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-border-color flex justify-between items-center" x-show="pagination.total > 0">
            <span class="text-sm text-secondary">Showing <span x-text="pagination.from"></span> to <span x-text="pagination.to"></span> of <span x-text="pagination.total"></span> roles</span>
            <div class="flex gap-2">
                <button @click="changePage(pagination.prev_page_url)" :disabled="!pagination.prev_page_url" class="px-3 py-1 rounded border border-input-border text-text-color disabled:opacity-50">Prev</button>
                <button @click="changePage(pagination.next_page_url)" :disabled="!pagination.next_page_url" class="px-3 py-1 rounded border border-input-border text-text-color disabled:opacity-50">Next</button>
            </div>
        </div>
    </div>

    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center px-4" x-transition.opacity>
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="isModalOpen = false"></div>
        <div class="relative w-full max-w-md bg-card-bg rounded-2xl shadow-2xl border border-border-color overflow-hidden" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            <div class="px-6 py-4 border-b border-border-color flex justify-between items-center bg-page-bg/30">
                <h3 class="text-lg font-bold text-text-color" x-text="editMode ? 'Edit Role Name' : 'Create New Role'"></h3>
                <button @click="isModalOpen = false" class="text-secondary hover:text-text-color"><i class="ri-close-line text-xl"></i></button>
            </div>
            <form @submit.prevent="submitForm" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-text-color mb-1">Role Name</label>
                    <input type="text" x-model="form.name" class="w-full px-4 py-2.5 rounded-lg border border-input-border bg-input-bg text-text-color focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="e.g., Manager">
                    <p x-show="errors.name" x-text="errors.name" class="text-red-500 text-xs mt-1"></p>
                </div>
                <div class="pt-2 flex justify-end gap-3">
                    <button type="button" @click="isModalOpen = false" class="px-4 py-2 rounded-lg border border-input-border text-text-color hover:bg-page-bg transition">Cancel</button>
                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:opacity-90 transition flex items-center gap-2" :disabled="isLoading">
                        <i x-show="isLoading" class="ri-loader-4-line animate-spin"></i>
                        <span x-text="editMode ? 'Update' : 'Save'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="isPermissionModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center px-4" x-transition.opacity>
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="isPermissionModalOpen = false"></div>
        <div class="relative w-full max-w-4xl bg-card-bg rounded-2xl shadow-2xl border border-border-color overflow-hidden flex flex-col max-h-[90vh]" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="px-6 py-4 border-b border-border-color flex justify-between items-center bg-page-bg/30 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center text-yellow-600">
                        <i class="ri-shield-keyhole-line text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-text-color">Assign Permissions</h3>
                        <p class="text-xs text-secondary">Role: <span class="font-bold text-primary" x-text="permissionForm.roleName"></span></p>
                    </div>
                </div>
                <button @click="isPermissionModalOpen = false" class="text-secondary hover:text-text-color"><i class="ri-close-line text-xl"></i></button>
            </div>

            <div class="overflow-y-auto p-6 bg-card-bg">
                <div class="flex justify-between items-center mb-4">
                    <label class="text-sm font-bold text-text-color">Select Permissions</label>
                    <div class="flex gap-3">
                        <button type="button" @click="selectAllPermissions()" class="text-xs text-primary font-bold hover:underline">Select All</button>
                        <button type="button" @click="permissionForm.permissions = []" class="text-xs text-red-500 font-bold hover:underline">Uncheck All</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($permissions as $perm)
                    <label class="flex items-center space-x-3 p-3 rounded-xl border border-input-border bg-input-bg hover:border-primary/50 cursor-pointer transition-all hover:shadow-sm select-none">
                        <div class="relative flex items-center">
                            <input type="checkbox" value="{{ $perm->name }}" x-model="permissionForm.permissions"
                                   class="peer w-5 h-5 cursor-pointer appearance-none rounded border border-input-border checked:bg-primary checked:border-primary transition-all">
                            <i class="ri-check-line absolute text-white text-sm opacity-0 peer-checked:opacity-100 pointer-events-none left-[2px]"></i>
                        </div>
                        <span class="text-sm text-text-color capitalize font-medium">{{ str_replace('-', ' ', $perm->name) }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="px-6 py-4 border-t border-border-color bg-page-bg/30 flex justify-end gap-3 flex-shrink-0">
                <button type="button" @click="isPermissionModalOpen = false" class="px-4 py-2 rounded-lg border border-input-border text-text-color hover:bg-page-bg transition">Cancel</button>
                <button type="button" @click="submitPermissions" class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:opacity-90 transition flex items-center gap-2" :disabled="isLoading">
                    <i x-show="isLoading" class="ri-loader-4-line animate-spin"></i>
                    <span x-text="isLoading ? 'Saving...' : 'Save Permissions'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function roleManagement() {
        return {
            roles: [], search: '', isLoading: false, pagination: {}, errors: {},
            
            // Modal 1: CRUD Role
            isModalOpen: false, editMode: false, form: { id: null, name: '' },

            // Modal 2: Assign Permissions
            isPermissionModalOpen: false, 
            permissionForm: { roleId: null, roleName: '', permissions: [] },

            async fetchRoles(url = "{{ route('admin.roles.fetch') }}") {
                if(this.search) url += (url.includes('?') ? '&' : '?') + `keyword=${this.search}`;
                try {
                    const res = await fetch(url);
                    const data = await res.json();
                    this.roles = data.data;
                    this.pagination = { total: data.total, from: data.from, to: data.to, prev_page_url: data.prev_page_url, next_page_url: data.next_page_url };
                } catch (e) { console.error(e); }
            },
            
            changePage(url) { if(url) this.fetchRoles(url); },

            // ------ ROLE CRUD LOGIC ------
            openModal(mode, role = null) {
                this.isModalOpen = true; this.errors = {};
                if (mode === 'edit') { this.editMode = true; this.form = { id: role.id, name: role.name }; }
                else { this.editMode = false; this.form = { id: null, name: '' }; }
            },

            async submitForm() {
                this.isLoading = true; this.errors = {};
                let url = this.editMode ? `/admin/roles/${this.form.id}` : "{{ route('admin.roles.store') }}";
                let method = this.editMode ? 'PUT' : 'POST';
                
                try {
                    const res = await fetch(url, {
                        method: method,
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify(this.form)
                    });
                    const data = await res.json();
                    if (!res.ok) {
                        if (res.status === 422) this.errors = data.errors;
                        else window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'error', message: 'Error!' } }));
                    } else {
                        this.isModalOpen = false; this.fetchRoles();
                        window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'success', message: data.message } }));
                    }
                } catch (e) { console.error(e); } finally { this.isLoading = false; }
            },

            // ------ PERMISSION ASSIGN LOGIC ------
            async openPermissionModal(role) {
                this.permissionForm.roleId = role.id;
                this.permissionForm.roleName = role.name;
                this.isPermissionModalOpen = true;
                
                // Fetch current permissions for this role
                try {
                    // កែមកប្រើ URL នេះ ដើម្បីទាញយក Permissions ដែលបានធីកស្រាប់
                    const res = await fetch(`/admin/assign-permissions/${role.id}`);
                    const data = await res.json();
                    this.permissionForm.permissions = data; 
                } catch (e) { console.error(e); }
            },

            selectAllPermissions() {
                const allPerms = @json($permissions->pluck('name'));
                this.permissionForm.permissions = allPerms;
            },

            async submitPermissions() {
                this.isLoading = true;

                // Debug: មើលថាតើទិន្នន័យត្រឹមត្រូវឬអត់? (មើលក្នុង Console)
                console.log('Sending Data:', { 
                    role_id: this.permissionForm.roleId, 
                    permissions: this.permissionForm.permissions 
                });

                try {
                    const res = await fetch("{{ route('admin.assign_permissions.update') }}", {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                        },
                        body: JSON.stringify({ 
                            role_id: this.permissionForm.roleId, 
                            permissions: this.permissionForm.permissions 
                        })
                    });

                    const data = await res.json();

                    if(res.ok) {
                        this.isPermissionModalOpen = false;
                        this.fetchRoles(); // Refresh table
                        window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'success', message: data.message } }));
                    } else {
                        // បង្ហាញ Error Message ដែល Server ផ្ញើមក (បើមាន)
                        let msg = data.message || 'Failed to assign permissions.';
                        window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'error', message: msg } }));
                        console.error('Server Error:', data);
                    }
                } catch (e) { 
                    console.error(e); 
                    window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'error', message: 'Network error occurred.' } }));
                } finally { 
                    this.isLoading = false; 
                }
            },
            
            async confirmDelete(id) {
                if(!confirm('Delete this role?')) return;
                await fetch(`/admin/roles/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } });
                this.fetchRoles();
                window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'success', message: 'Role deleted' } }));
            }
        }
    }
</script>
@endsection