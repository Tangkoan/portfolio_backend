<div class="flex flex-col gap-3">

    {{-- Select All Row --}}
    <div class="flex items-center justify-between px-2" x-show="experiences?.length > 0" x-cloak>
        <label class="flex items-center gap-2 text-sm font-bold text-text-color select-none cursor-pointer">
            <input type="checkbox" @change="toggleSelectAll()" x-model="selectAll" 
                   @cannot('experiences-delete') disabled @endcannot
                   class="rounded border-input-border text-primary focus:ring-primary h-5 w-5 disabled:opacity-50 disabled:cursor-not-allowed">
            <span :class="{'opacity-50': {{ auth()->user()->cannot('experiences-delete') ? 'true' : 'false' }}}">{{ __('messages.select_all') }}</span>
        </label>
        <span class="text-xs text-secondary"><span x-text="experiences?.length"></span> {{ __('messages.items') }}</span>
    </div>

    <template x-for="item in experiences" :key="'mobile-' + item.id">
        <div class="bg-card-bg p-4 rounded-2xl shadow-sm border border-border-color relative overflow-hidden transition-all duration-200"
             :class="{'ring-2 ring-primary bg-primary/5': selectedIds.includes(item.id)}">
            
            <input type="checkbox" :value="item.id" x-model="selectedIds" 
                   @cannot('experiences-delete') disabled @endcannot
                   class="absolute top-4 left-4 z-20 rounded-md border-gray-300 text-primary focus:ring-primary h-5 w-5 shadow-sm bg-white disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-200">

            <div class="flex flex-col gap-1 pl-8">
                <h3 class="font-bold text-text-color text-lg" x-text="item.name"></h3>
                <p class="text-sm font-semibold text-primary" x-show="showCols.company">
                    <i class="ri-building-line mr-1"></i><span x-text="item.sup_name"></span>
                </p>
                <p class="text-xs text-secondary mt-1" x-show="showCols.duration">
                    <i class="ri-calendar-line mr-1"></i>
                    <span x-text="item.start_day"></span> - <span x-text="item.end_day ? item.end_day : '{{ __('messages.present') }}'"></span>
                </p>

                <div class="flex items-center justify-between mt-3 pt-3 border-t border-dashed border-border-color">
                    
                    {{-- Status --}}
                    <div class="flex items-center gap-2" x-show="showCols.status">
                        <button 
                            type="button"
                            @can('experiences-edit-status')
                                @click="toggleStatus(item.id)"
                            @else
                                disabled
                            @endcan
                            class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed" 
                            :class="item.status ? 'bg-green-500' : 'bg-gray-300'"
                        >
                            <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform shadow-sm" :class="item.status ? 'translate-x-4' : 'translate-x-0.5'"></span>
                        </button>
                        <span class="text-[10px] font-bold uppercase" :class="item.status ? 'text-green-600' : 'text-gray-400'" 
                              x-text="item.status ? '{{ __('messages.status_active') }}' : '{{ __('messages.status_inactive') }}'"></span>
                    </div>
                    
                    {{-- Actions --}}
                    <div class="flex gap-2">
                        <button 
                            type="button"
                            @can('experiences-edit')
                                @click="openModal('edit', item)"
                            @else
                                disabled
                            @endcan
                            class="h-8 w-8 rounded-full flex items-center justify-center bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-100 active:scale-95 transition-transform disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-blue-50 disabled:active:scale-100"
                        >
                            <i class="ri-pencil-fill"></i>
                        </button>
                        <button 
                            type="button"
                            @can('experiences-delete')
                                @click="confirmDelete(item.id)"
                            @else
                                disabled
                            @endcan
                            class="h-8 w-8 rounded-full flex items-center justify-center bg-red-50 text-red-600 border border-red-100 hover:bg-red-100 active:scale-95 transition-transform disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-red-50 disabled:active:scale-100"
                        >
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{-- No data --}}
    <div x-show="experiences?.length === 0" x-cloak class="text-center py-10 text-secondary bg-card-bg rounded-xl border border-dashed border-border-color">
        <i class="ri-briefcase-4-line text-4xl mb-2 inline-block opacity-50"></i>
        <p>{{ __('messages.no_experiences_found') }}</p>
    </div>

</div>