<div class="flex flex-col gap-3">
    <div class="flex items-center justify-between px-2" x-show="socials?.length > 0" x-cloak>
        <label class="flex items-center gap-2 text-sm font-bold text-text-color select-none cursor-pointer">
            <input type="checkbox" @change="toggleSelectAll()" x-model="selectAll" 
                   @cannot('socials-delete') disabled @endcannot
                   class="rounded border-input-border text-primary focus:ring-primary h-5 w-5 disabled:opacity-50 disabled:cursor-not-allowed">
            <span :class="{'opacity-50': {{ auth()->user()->cannot('socials-delete') ? 'true' : 'false' }}}">{{ __('messages.select_all') }}</span>
        </label>
        <span class="text-xs text-secondary"><span x-text="socials?.length"></span> {{ __('messages.items') }}</span>
    </div>

    <template x-for="item in socials" :key="'mobile-' + item.id">
        <div class="bg-card-bg p-4 rounded-2xl shadow-sm border border-border-color relative overflow-hidden transition-all duration-200" :class="{'ring-2 ring-primary bg-primary/5': selectedIds.includes(item.id)}">
            
            <input type="checkbox" :value="item.id" x-model="selectedIds" 
                   @cannot('socials-delete') disabled @endcannot
                   class="absolute top-4 left-4 z-20 rounded-md border-gray-300 text-primary focus:ring-primary h-5 w-5 shadow-sm bg-white disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-200">
            
            <div class="flex flex-col gap-3 pl-8"> 
                <div class="flex items-center gap-3">
                    <div class="relative shrink-0" x-show="showCols.image">
                        <div class="h-12 w-12 rounded-xl bg-gray-100 overflow-hidden border border-border-color">
                            <template x-if="item.image"><img :src="'/storage/' + item.image" class="w-full h-full object-cover p-1"></template>
                            <template x-if="!item.image"><div class="w-full h-full flex items-center justify-center text-secondary"><i class="ri-links-line text-xl"></i></div></template>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-text-color text-base truncate" x-text="item.name"></h3>
                    </div>
                </div>
                
                <div x-show="showCols.link">
                    <a :href="item.url_social" target="_blank" class="text-blue-500 hover:text-blue-700 hover:underline flex items-center gap-1 text-sm bg-blue-50 px-3 py-1.5 rounded-lg w-full truncate"><i class="ri-external-link-line shrink-0"></i> <span class="truncate" x-text="item.url_social"></span></a>
                </div>
                
                <div class="flex items-center justify-between mt-2 pt-3 border-t border-dashed border-border-color">
                    <div class="flex items-center gap-2" x-show="showCols.status">
                        <button 
                            type="button" 
                            @can('socials-edit-status')
                                @click="toggleStatus(item.id)" 
                            @else
                                disabled
                            @endcan
                            class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed" 
                            :class="item.status ? 'bg-green-500' : 'bg-gray-300'"
                        >
                            <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform shadow-sm" :class="item.status ? 'translate-x-4' : 'translate-x-0.5'"></span>
                        </button>
                        <span class="text-[10px] font-bold uppercase" :class="item.status ? 'text-green-600' : 'text-gray-400'" x-text="item.status ? '{{ __('messages.status_active') }}' : '{{ __('messages.status_inactive') }}'"></span>
                    </div>

                    <div class="flex gap-2">
                        <button 
                            type="button" 
                            @can('socials-edit')
                                @click="openModal('edit', item)" 
                            @else
                                disabled
                            @endcan
                            class="h-8 w-8 rounded-full flex items-center justify-center bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-100 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-blue-50"
                        >
                            <i class="ri-pencil-fill"></i>
                        </button>
                        <button 
                            type="button" 
                            @can('socials-delete')
                                @click="openDeleteModal('single', item.id)" 
                            @else
                                disabled
                            @endcan
                            class="h-8 w-8 rounded-full flex items-center justify-center bg-red-50 text-red-600 border border-red-100 hover:bg-red-100 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-red-50"
                        >
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
    
    <div x-show="socials?.length === 0" x-cloak class="text-center py-10 text-secondary bg-card-bg rounded-xl border border-dashed border-border-color"><i class="ri-links-line text-4xl mb-2 inline-block opacity-50"></i><p>{{ __('messages.no_socials_found') }}</p></div>
</div>