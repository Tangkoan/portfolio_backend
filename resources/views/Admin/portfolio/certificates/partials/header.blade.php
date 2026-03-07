<div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 mb-4">
    <h1 class="flex items-center gap-2 text-xl sm:text-2xl font-bold text-text-color">
        <i class="ri-article-line"></i> Certifications Management
    </h1>
    <div class="flex items-center gap-2 w-full xl:w-auto">
        <button @click="openModal('create')" class="w-full xl:w-auto btn-primary flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-primary/30 hover:opacity-90">
            <i class="ri-upload-2-line text-xl"></i><span>Upload Certificate</span>
        </button>
    </div>
</div>

<div class="flex flex-col md:flex-row justify-between items-center gap-3 mb-6">
    <div class="text-sm text-secondary">Manage and organize your certification images.</div>
    <div x-show="selectedIds.length > 0" x-transition class="flex items-center gap-2 bg-primary/10 border border-primary/20 p-2 rounded-xl">
        <span class="text-xs font-bold text-primary px-2"><span x-text="selectedIds.length"></span> Selected</span>
        <div class="flex gap-1">
            <button @click="startSequentialEdit()" class="h-8 w-8 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200"><i class="ri-edit-circle-line"></i></button>
            <button @click="openDeleteModal('bulk')" class="h-8 w-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200"><i class="ri-delete-bin-line"></i></button>
        </div>
    </div>
</div>