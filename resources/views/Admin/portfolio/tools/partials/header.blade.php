<div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 mb-4">
    <h1 class="flex items-center gap-2 text-xl sm:text-2xl font-bold text-text-color">
        <i class="ri-tools-line"></i> Tools Management
    </h1>
    <div class="hidden md:flex items-center gap-2">
        <button @click="openModal('create')" class="btn-primary flex items-center gap-2 px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-primary/30 hover:opacity-90">
            <i class="ri-add-circle-line text-xl"></i><span>Add Tool</span>
        </button>
    </div>
</div>

<div class="flex flex-col md:flex-row gap-3 mb-6">
    <div class="relative flex-1">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-secondary pointer-events-none">
            <i class="ri-search-line"></i>
        </span>
        <input type="text" x-model="search" @keyup.debounce.500ms="fetchTools()" placeholder="Search tool by name..." class="w-full pl-9 pr-3 py-2.5 text-sm rounded-xl border border-input-border bg-card-bg text-text-color shadow-sm focus:ring-primary outline-none">
    </div>

    <div class="relative shrink-0">
        <button @click="openCol=!openCol" @click.outside="openCol=false" class="h-[42px] px-3 flex items-center justify-center gap-1 rounded-xl border border-input-border bg-card-bg text-text-color text-sm shadow-sm hover:bg-input-bg">
            <i class="ri-layout-column-line text-lg"></i><span class="hidden md:inline ml-1">Columns</span>
        </button>

        <div x-show="openCol" x-transition class="absolute right-0 mt-2 w-44 bg-card-bg border border-border-color rounded-xl shadow-xl z-50 p-2">
            <div class="space-y-1">
                <label class="flex items-center gap-2 px-2 py-1.5 hover:bg-page-bg rounded cursor-pointer">
                    <input type="checkbox" x-model="showCols.image" class="rounded border-input-border text-primary focus:ring-primary"><span class="text-sm text-text-color">Image</span>
                </label>
                <label class="flex items-center gap-2 px-2 py-1.5 hover:bg-page-bg rounded cursor-pointer">
                    <input type="checkbox" x-model="showCols.status" class="rounded border-input-border text-primary focus:ring-primary"><span class="text-sm text-text-color">Status</span>
                </label>
            </div>
        </div>
    </div>

    <div class="flex md:hidden">
        <button @click="openModal('create')" class="w-full flex items-center justify-center gap-2 bg-primary text-white py-2.5 px-6 rounded-xl font-bold shadow-lg shadow-primary/30">
            <i class="ri-add-circle-line text-xl"></i><span>Add Tool</span>
        </button>
    </div>

    <div x-show="selectedIds.length > 0" x-transition class="flex items-center justify-between gap-2 w-full md:w-auto bg-primary/10 border border-primary/20 p-2 rounded-xl">
        <span class="text-xs font-bold text-primary px-2"><span x-text="selectedIds.length"></span> Selected</span>
        <div class="flex gap-1">
            <button @click="startSequentialEdit()" class="h-8 w-8 flex items-center justify-center rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200"><i class="ri-edit-circle-line"></i></button>
            <button @click="openDeleteModal('bulk')" class="h-8 w-8 flex items-center justify-center rounded-lg bg-red-100 text-red-600 hover:bg-red-200"><i class="ri-delete-bin-line"></i></button>
        </div>
    </div>
</div>