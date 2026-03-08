<div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 mb-4">

    {{-- Title --}}
    <h1 class="flex items-center gap-2 text-xl sm:text-2xl font-bold text-text-color">
        <i class="ri-cpu-line"></i>
        {{ __('messages.technology_management') }}
    </h1>

    {{-- Desktop Action --}}
    <div class="hidden md:flex items-center gap-2">
        <button
            type="button"
            @can('technologies-create')
                @click="openModal('create')"
            @else
                disabled
            @endcan
            class="btn-primary flex items-center gap-2 px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-primary/30 hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:opacity-50">
            <i class="ri-add-circle-line text-xl"></i>
            <span>{{ __('messages.add_technology') }}</span>
        </button>
    </div>

</div>

{{-- Filter Section --}}
<div class="flex flex-col md:flex-row gap-3 mb-6">

    {{-- Search --}}
    <div class="relative flex-1">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-secondary pointer-events-none">
            <i class="ri-search-line"></i>
        </span>
        <input
            type="text"
            x-model="search"
            @keyup.debounce.500ms="fetchTechnologies()"
            placeholder="{{ __('messages.search_technology') }}"
            class="w-full pl-9 pr-3 py-2.5 text-sm rounded-xl border border-input-border bg-card-bg text-text-color shadow-sm focus:ring-2 focus:ring-primary/20 outline-none">
    </div>

    {{-- Column Toggle --}}
    <div class="relative shrink-0">
        <button
            type="button"
            @click="openCol=!openCol"
            @click.outside="openCol=false"
            class="h-[42px] px-3 flex items-center justify-center gap-1 rounded-xl border border-input-border bg-card-bg text-text-color text-sm shadow-sm hover:bg-input-bg">
            <i class="ri-layout-column-line text-lg"></i>
            <span class="hidden md:inline ml-1">{{ __('messages.columns') }}</span>
        </button>

        <div
            x-show="openCol"
            x-transition
            class="absolute right-0 mt-2 w-44 bg-card-bg border border-border-color rounded-xl shadow-xl z-50 p-2" x-cloak>
            <div class="space-y-1">
                <label class="flex items-center gap-2 px-2 py-1.5 hover:bg-page-bg rounded cursor-pointer">
                    <input type="checkbox" x-model="showCols.image" class="rounded border-input-border text-primary focus:ring-primary">
                    <span class="text-sm text-text-color">{{ __('messages.image') }}</span>
                </label>
                <label class="flex items-center gap-2 px-2 py-1.5 hover:bg-page-bg rounded cursor-pointer">
                    <input type="checkbox" x-model="showCols.status" class="rounded border-input-border text-primary focus:ring-primary">
                    <span class="text-sm text-text-color">{{ __('messages.status') }}</span>
                </label>
                <label class="flex items-center gap-2 px-2 py-1.5 hover:bg-page-bg rounded cursor-pointer">
                    <input type="checkbox" x-model="showCols.created" class="rounded border-input-border text-primary focus:ring-primary">
                    <span class="text-sm text-text-color">{{ __('messages.created_date') }}</span>
                </label>
            </div>
        </div>
    </div>

    {{-- Mobile Button --}}
    <div class="flex md:hidden">
        <button
            type="button"
            @can('technologies-create')
                @click="openModal('create')"
            @else
                disabled
            @endcan
            class="w-full flex items-center justify-center gap-2 bg-primary text-white py-2.5 px-6 rounded-xl font-bold shadow-lg shadow-primary/30 disabled:opacity-50 disabled:cursor-not-allowed">
            <i class="ri-add-circle-line text-xl"></i>
            <span>{{ __('messages.add_technology') }}</span>
        </button>
    </div>

    {{-- Selected Items --}}
    <div
        x-show="selectedIds.length > 0"
        x-transition
        class="flex items-center justify-between gap-2 w-full md:w-auto bg-primary/10 border border-primary/20 p-2 rounded-xl" x-cloak>

        <span class="text-xs font-bold text-primary px-2">
            <span x-text="selectedIds.length"></span> {{ __('messages.selected') }}
        </span>

        <div class="flex gap-1">
            <button
                type="button"
                @can('technologies-edit')
                    @click="startSequentialEdit()"
                @else
                    disabled
                @endcan
                class="h-8 w-8 flex items-center justify-center rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-blue-100">
                <i class="ri-edit-circle-line"></i>
            </button>

            <button
                type="button"
                @can('technologies-delete')
                    @click="confirmBulkDelete()"
                @else
                    disabled
                @endcan
                class="h-8 w-8 flex items-center justify-center rounded-lg bg-red-100 text-red-600 hover:bg-red-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-red-100">
                <i class="ri-delete-bin-line"></i>
            </button>
        </div>
    </div>
</div>