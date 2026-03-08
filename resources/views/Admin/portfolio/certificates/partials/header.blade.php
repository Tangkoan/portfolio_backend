<div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 mb-4">
    <h1 class="flex items-center gap-2 text-xl sm:text-2xl font-bold text-text-color">
        <i class="ri-article-line"></i> {{ __('messages.certifications_management') }}
    </h1>
    <div class="flex items-center gap-2 w-full xl:w-auto">
        <button 
            type="button"
            @can('certificates-create')
                @click="openModal('create')" 
            @else
                disabled
            @endcan
            class="w-full xl:w-auto btn-primary flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-primary/30 hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:opacity-50"
        >
            <i class="ri-upload-2-line text-xl"></i><span>{{ __('messages.upload_certificate') }}</span>
        </button>
    </div>
</div>

<div class="flex flex-col md:flex-row justify-between items-center gap-3 mb-6">
    <div class="text-sm text-secondary">{{ __('messages.manage_cert_desc') }}</div>
    
    <div x-show="selectedIds.length > 0" x-transition class="flex items-center gap-2 bg-primary/10 border border-primary/20 p-2 rounded-xl" x-cloak>
        <span class="text-xs font-bold text-primary px-2"><span x-text="selectedIds.length"></span> {{ __('messages.selected') }}</span>
        <div class="flex gap-1">
            <button 
                type="button"
                @can('certificates-edit')
                    @click="startSequentialEdit()" 
                @else
                    disabled
                @endcan
                class="h-8 w-8 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-blue-100"
            >
                <i class="ri-edit-circle-line"></i>
            </button>
            <button 
                type="button"
                @can('certificates-delete')
                    @click="openDeleteModal('bulk')" 
                @else
                    disabled
                @endcan
                class="h-8 w-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-red-100"
            >
                <i class="ri-delete-bin-line"></i>
            </button>
        </div>
    </div>
</div>