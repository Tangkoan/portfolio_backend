@extends('admin.dashboard')

@section('content')
<div class="w-full h-full px-6 py-5" 
     x-data="permissionManagement()" 
     x-init="fetchPermissions()">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-text-color flex items-center gap-2">
                <i class="ri-key-2-line text-primary"></i> Permission Management
            </h1>
            <p class="text-sm text-secondary mt-1">Create and manage system permissions.</p>
        </div>

        <div class="flex gap-3 w-full md:w-auto">
            <div class="relative w-full md:w-64">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-secondary">
                    <i class="ri-search-line"></i>
                </span>
                <input type="text" x-model="search" @keyup.debounce.500ms="fetchPermissions()"
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-input-border bg-card-bg text-text-color focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder-secondary"
                       placeholder="Search permissions...">
            </div>

            <button 
                @role('Super Admin') @click="openModal('create')" @endrole
                class="bg-primary text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-primary/30 flex items-center gap-2 transition-all
                       @unlessrole('Super Admin') opacity-50 cursor-not-allowed @else hover:opacity-90 @endunlessrole"
                @unlessrole('Super Admin') disabled title="Only Super Admin can create permissions" @endunlessrole>
                <i class="ri-add-line"></i> <span class="hidden sm:inline">Add Permission</span>
            </button>
        </div>
    </div>

    <div class="bg-card-bg rounded-xl shadow-custom border border-border-color overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-page-bg/50 border-b border-border-color text-text-color text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">Permission Name</th>
                        <th class="px-6 py-4 font-bold">Guard Name</th>
                        <th class="px-6 py-4 font-bold">Created At</th>
                        <th class="px-6 py-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-color">
                    <template x-for="perm in permissions" :key="perm.id">
                        <tr class="hover:bg-page-bg/30 transition-colors group">
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-lg bg-input-bg border border-input-border flex items-center justify-center text-secondary">
                                        <i class="ri-shield-keyhole-line"></i>
                                    </div>
                                    <span class="font-bold text-text-color text-lg" x-text="perm.name"></span>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-xs font-medium bg-blue-50 text-blue-600 border border-blue-200" x-text="perm.guard_name"></span>
                            </td>

                            <td class="px-6 py-4 text-secondary text-sm" x-text="new Date(perm.created_at).toLocaleDateString()"></td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    
                                    <button 
                                        @role('Super Admin') @click="openModal('edit', perm)" @endrole
                                        class="h-8 w-8 rounded-lg flex items-center justify-center transition-colors
                                               @role('Super Admin') bg-blue-50 dark:bg-blue-900/20 text-blue-600 hover:bg-blue-100 @else bg-gray-100 text-gray-400 cursor-not-allowed @endrole"
                                        @unlessrole('Super Admin') disabled title="Restricted" @endunlessrole>
                                        <i class="ri-pencil-line"></i>
                                    </button>

                                    <button 
                                        @role('Super Admin') @click="confirmDelete(perm.id)" @endrole
                                        class="h-8 w-8 rounded-lg flex items-center justify-center transition-colors
                                               @role('Super Admin') bg-red-50 dark:bg-red-900/20 text-red-600 hover:bg-red-100 @else bg-gray-100 text-gray-400 cursor-not-allowed @endrole"
                                        @unlessrole('Super Admin') disabled title="Restricted" @endunlessrole>
                                        <i class="ri-delete-bin-line"></i>
                                    </button>

                                </div>
                            </td>
                        </tr>
                    </template>
                    
                    <tr x-show="permissions.length === 0">
                        <td colspan="4" class="px-6 py-12 text-center text-secondary">
                            <i class="ri-file-search-line text-4xl mb-2 inline-block opacity-50"></i>
                            <p>No permissions found.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-border-color flex justify-between items-center" x-show="pagination.total > 0">
            <span class="text-sm text-secondary">Showing <span x-text="pagination.from"></span> to <span x-text="pagination.to"></span> of <span x-text="pagination.total"></span> results</span>
            <div class="flex gap-2">
                <button @click="changePage(pagination.prev_page_url)" :disabled="!pagination.prev_page_url" class="px-3 py-1 rounded border border-input-border text-text-color disabled:opacity-50">Prev</button>
                <button @click="changePage(pagination.next_page_url)" :disabled="!pagination.next_page_url" class="px-3 py-1 rounded border border-input-border text-text-color disabled:opacity-50">Next</button>
            </div>
        </div>
    </div>

    <div x-show="isModalOpen" 
         style="display: none;"
         class="fixed inset-0 z-[100] flex items-center justify-center px-4"
         x-transition.opacity.duration.300ms>
        
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="isModalOpen = false"></div>

        <div class="relative w-full max-w-md bg-card-bg rounded-2xl shadow-2xl border border-border-color overflow-hidden"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="px-6 py-4 border-b border-border-color flex justify-between items-center bg-page-bg/30">
                <h3 class="text-lg font-bold text-text-color" x-text="editMode ? 'Edit Permission' : 'Create Permission'"></h3>
                <button @click="isModalOpen = false" class="text-secondary hover:text-text-color"><i class="ri-close-line text-xl"></i></button>
            </div>

            <form @submit.prevent="submitForm" class="p-6 space-y-4">
                
                <div>
                    <label class="block text-sm font-bold text-text-color mb-1">Permission Name</label>
                    <input type="text" x-model="form.name" 
                           class="w-full px-4 py-2.5 rounded-lg border border-input-border bg-input-bg text-text-color focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" 
                           placeholder="e.g., user-create">
                    <p class="text-xs text-secondary mt-1">Suggested format: <code>resource-action</code> (e.g., post-edit)</p>
                    <p x-show="errors.name" x-text="errors.name" class="text-red-500 text-xs mt-1"></p>
                </div>

                <div class="pt-2 flex justify-end gap-3 border-t border-border-color mt-4">
                    <button type="button" @click="isModalOpen = false" class="px-4 py-2 rounded-lg border border-input-border text-text-color hover:bg-page-bg transition">Cancel</button>
                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:opacity-90 transition flex items-center gap-2" :disabled="isLoading">
                        <i x-show="isLoading" class="ri-loader-4-line animate-spin"></i>
                        <span x-text="editMode ? 'Update' : 'Save'"></span>
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

<script>
    function permissionManagement() {
        return {
            permissions: [], search: '', isModalOpen: false, editMode: false, isLoading: false, pagination: {}, form: { id: null, name: '' }, errors: {},

            async fetchPermissions(url = "{{ route('admin.permissions.fetch') }}") {
                if(this.search) url += (url.includes('?') ? '&' : '?') + `keyword=${this.search}`;
                try {
                    const res = await fetch(url);
                    const data = await res.json();
                    this.permissions = data.data;
                    this.pagination = { total: data.total, from: data.from, to: data.to, prev_page_url: data.prev_page_url, next_page_url: data.next_page_url };
                } catch (e) { console.error(e); }
            },
            
            changePage(url) { if(url) this.fetchPermissions(url); },

            openModal(mode, perm = null) {
                this.isModalOpen = true;
                this.errors = {};
                if (mode === 'edit') { this.editMode = true; this.form = { id: perm.id, name: perm.name }; }
                else { this.editMode = false; this.form = { id: null, name: '' }; }
            },

            async submitForm() {
                this.isLoading = true; this.errors = {};
                let url = this.editMode ? `/admin/permissions/${this.form.id}` : "{{ route('admin.permissions.store') }}";
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
                        else window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'error', message: 'Error occurred!' } }));
                    } else {
                        this.isModalOpen = false; this.fetchPermissions();
                        window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'success', message: data.message } }));
                    }
                } catch (e) { console.error(e); } finally { this.isLoading = false; }
            },
            
            async confirmDelete(id) {
                if(!confirm('Delete this permission?')) return;
                try {
                    const res = await fetch(`/admin/permissions/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    });
                    const data = await res.json();
                    if(res.ok) {
                        this.fetchPermissions();
                        window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'success', message: data.message } }));
                    }
                } catch (e) { console.error(e); }
            }
        }
    }
</script>
@endsection