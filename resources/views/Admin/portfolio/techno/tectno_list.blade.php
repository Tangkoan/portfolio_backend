@extends('admin.dashboard')

@section('content')
<div class="w-full h-full px-1 py-1" x-data="technoManagement()">
    
    <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-text-color flex items-center gap-2">
            <i class="ri-cpu-line"></i> {{ __('messages.techno_management') }}
        </h1>

        <div class="flex flex-col sm:flex-row items-center gap-3 w-full xl:w-auto">
            <div x-show="selectedIds.length > 0" x-transition 
                 class="flex items-center gap-2 bg-white dark:bg-gray-800 p-1 rounded-lg border border-border-color shadow-sm">
                <span class="text-xs font-bold text-primary px-2" x-text="selectedIds.length + ' {{ __('messages.selected_items') }}'"></span>
                <button @click="startSequentialEdit()" class="text-blue-600 p-1.5 hover:bg-blue-50 rounded" title="Edit Selected"><i class="ri-edit-circle-line"></i></button>
                <button @click="confirmBulkDelete()" class="text-red-600 p-1.5 hover:bg-red-50 rounded" title="Delete Selected"><i class="ri-delete-bin-line"></i></button>
            </div>

            <div class="relative w-full sm:w-64">
                <input type="text" x-model="search" @keyup.debounce.500ms="fetchData()"
                       class="w-full pl-4 pr-4 py-2.5 rounded-xl border border-input-border bg-card-bg text-sm"
                       placeholder="{{ __('messages.search_placeholder') }}">
            </div>

            <button @click="openModal('create')" class="bg-primary text-white py-2.5 px-6 rounded-xl font-bold flex items-center gap-2">
                <i class="ri-add-line"></i> {{ __('messages.add_techno') }}
            </button>
        </div>
    </div>

    <div class="bg-card-bg rounded-xl shadow-custom border border-border-color overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-page-bg/50 border-b border-border-color text-sm uppercase">
                        <th class="px-6 py-4 w-4">
                            <input type="checkbox" @change="toggleSelectAll()" x-model="selectAll" class="rounded">
                        </th>
                        <th class="px-6 py-4">{{ __('messages.image') }}</th>
                        <th class="px-6 py-4">{{ __('messages.techno_name') }}</th>
                        <th class="px-6 py-4">{{ __('messages.status') }}</th>
                        <th class="px-6 py-4 text-right">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-color">
                    <template x-for="item in list" :key="item.id">
                        <tr class="hover:bg-page-bg/30 transition" :class="{'bg-primary/5': selectedIds.includes(item.id)}">
                            <td class="px-6 py-4">
                                <input type="checkbox" :value="item.id" x-model="selectedIds" class="rounded">
                            </td>
                            <td class="px-6 py-4">
                                <img :src="item.image ? '/storage/' + item.image : '/images/no-image.png'" class="w-10 h-10 object-cover rounded-lg border">
                            </td>
                            <td class="px-6 py-4 font-bold text-text-color" x-text="item.name"></td>
                            <td class="px-6 py-4">
                                <span x-text="item.status == 1 ? 'Active' : 'Inactive'" 
                                      :class="item.status == 1 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'"
                                      class="px-2 py-1 rounded-md text-xs font-bold uppercase"></span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button @click="openModal('edit', item)" class="h-8 w-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center"><i class="ri-pencil-line"></i></button>
                                    <button @click="confirmDelete(item.id)" class="h-8 w-8 bg-red-50 text-red-600 rounded-lg flex items-center justify-center"><i class="ri-delete-bin-line"></i></button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <x-pagination />
    </div>

    <div x-show="isModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center px-4" x-cloak>
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeModal()"></div>
        <div class="relative w-full max-w-lg bg-card-bg rounded-2xl shadow-2xl overflow-hidden" x-transition>
            <div class="px-6 py-4 border-b flex justify-between items-center" :class="isSequenceMode ? 'bg-blue-50' : 'bg-page-bg/30'">
                <h3 class="text-lg font-bold" x-text="editMode ? '{{ __('messages.edit_techno') }}' : '{{ __('messages.add_techno') }}'"></h3>
                <button @click="closeModal()" class="text-secondary"><i class="ri-close-line text-xl"></i></button>
            </div>
            
            <form @submit.prevent="submitForm" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold mb-1">{{ __('messages.techno_name') }}</label>
                    <input type="text" x-model="form.name" class="w-full px-4 py-2 rounded-lg border border-input-border bg-input-bg">
                    <p x-show="errors.name" x-text="errors.name" class="text-red-500 text-xs mt-1"></p>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">{{ __('messages.status') }}</label>
                    <select x-model="form.status" class="w-full px-4 py-2 rounded-lg border border-input-border bg-input-bg">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">{{ __('messages.image') }}</label>
                    <input type="file" @change="handleFileUpload" class="w-full text-sm">
                    <template x-if="imagePreview">
                        <img :src="imagePreview" class="mt-2 w-20 h-20 object-cover rounded-lg border">
                    </template>
                </div>

                <div class="pt-4 flex justify-between items-center border-t">
                    <button type="button" x-show="isSequenceMode" @click="nextInSequence()" class="text-sm font-bold text-secondary">Skip</button>
                    <div class="flex gap-3 ml-auto">
                        <button type="button" @click="closeModal()" class="px-4 py-2 border rounded-lg">Cancel</button>
                        <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg" :disabled="isLoading">
                            <span x-text="isSequenceMode ? (currentSeqIndex + 1 === sequenceQueue.length ? 'Finish' : 'Save & Next') : 'Save'"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function technoManagement() {
    return {
        list: [], search: '', selectedIds: [], selectAll: false,
        isModalOpen: false, editMode: false, isLoading: false,
        isSequenceMode: false, sequenceQueue: [], currentSeqIndex: 0,
        form: { id: null, name: '', status: 1, image: null },
        errors: {}, imagePreview: null,

        init() { this.fetchData(); },

        async fetchData() {
            let url = "{{ route('admin.technologies.fetch') }}?keyword=" + this.search;
            const res = await fetch(url);
            const data = await res.json();
            this.list = data.data;
        },

        handleFileUpload(e) {
            const file = e.target.files[0];
            this.form.image = file;
            this.imagePreview = URL.createObjectURL(file);
        },

        openModal(mode, item = null) {
            this.isModalOpen = true;
            this.errors = {};
            this.imagePreview = null;
            if (mode === 'edit') {
                this.editMode = true;
                this.form = { id: item.id, name: item.name, status: item.status, image: null };
                if(item.image) this.imagePreview = '/storage/' + item.image;
            } else {
                this.editMode = false;
                this.form = { id: null, name: '', status: 1, image: null };
            }
        },

        async submitForm() {
            this.isLoading = true;
            let formData = new FormData();
            formData.append('name', this.form.name);
            formData.append('status', this.form.status);
            if (this.form.image) formData.append('image', this.form.image);
            if (this.editMode) formData.append('_method', 'PUT');

            let url = this.editMode ? `/admin/portfolio/technologies/${this.form.id}` : "{{ route('admin.technologies.store') }}";

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                const data = await res.json();
                if (res.ok) {
                    if (this.isSequenceMode) {
                        this.nextInSequence();
                    } else {
                        this.isModalOpen = false;
                        this.fetchData();
                    }
                    window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'success', message: data.message } }));
                } else {
                    this.errors = data.errors || {};
                }
            } finally { this.isLoading = false; }
        },

        toggleSelectAll() {
            this.selectedIds = this.selectAll ? this.list.map(i => i.id) : [];
        },

        startSequentialEdit() {
            this.sequenceQueue = this.list.filter(i => this.selectedIds.includes(i.id));
            this.isSequenceMode = true;
            this.currentSeqIndex = 0;
            this.openModal('edit', this.sequenceQueue[0]);
        },

        nextInSequence() {
            this.currentSeqIndex++;
            if (this.currentSeqIndex < this.sequenceQueue.length) {
                this.openModal('edit', this.sequenceQueue[this.currentSeqIndex]);
            } else {
                this.closeModal();
                this.fetchData();
            }
        },

        closeModal() {
            this.isModalOpen = false;
            this.isSequenceMode = false;
            this.selectedIds = [];
            this.selectAll = false;
        },

        confirmDelete(id) {
            if(!confirm('Are you sure?')) return;
            fetch(`/admin/portfolio/technologies/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(() => this.fetchData());
        },

        confirmBulkDelete() {
            if(!confirm('Delete selected items?')) return;
            fetch("{{ route('admin.technologies.bulk_delete') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ ids: this.selectedIds })
            }).then(() => {
                this.selectedIds = [];
                this.fetchData();
            });
        }
    }
}
</script>
@endsection