<div class="flex flex-col gap-3">

    {{-- Select All Row --}}
    <div class="flex items-center justify-between px-2" x-show="technologies.length > 0">
        <label class="flex items-center gap-2 text-sm font-bold text-text-color select-none cursor-pointer">
            <input type="checkbox" @change="toggleSelectAll()" x-model="selectAll" class="rounded border-input-border text-primary focus:ring-primary h-5 w-5">
            <span>{{ __('messages.select_all') }}</span>
        </label>
        <span class="text-xs text-secondary"><span x-text="technologies.length"></span> {{ __('messages.items') }}</span>
    </div>

    <template x-for="tech in technologies" :key="'mobile-' + tech.id">
        <div class="bg-card-bg p-3 rounded-2xl shadow-sm border border-border-color relative overflow-hidden transition-all duration-200"
             :class="{'ring-2 ring-primary bg-primary/5': selectedIds.includes(tech.id)}">
            
            {{-- Checkbox --}}
            <input type="checkbox" :value="tech.id" x-model="selectedIds" 
                   class="absolute top-3 left-3 z-20 rounded-md border-gray-300 text-primary focus:ring-primary h-5 w-5 shadow-sm bg-white">

            <div class="flex gap-3 pl-1"> 
                
                {{-- Left: Image Area --}}
                <div class="relative shrink-0" x-show="showCols.image">
                    <div class="h-20 w-20 rounded-xl bg-gray-100 overflow-hidden border border-border-color">
                        <template x-if="tech.image"><img :src="'/storage/' + tech.image" class="w-full h-full object-cover"></template>
                        <template x-if="!tech.image"><div class="w-full h-full flex items-center justify-center text-secondary"><i class="ri-image-line text-2xl"></i></div></template>
                    </div>
                </div>

                {{-- Right: Content --}}
                <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5 pl-3" >
                    
                    {{-- Name --}}
                    <h3 class="font-bold text-text-color text-base truncate" x-text="tech.name"></h3>

                    {{-- Status --}}
                    <div class="flex items-center justify-between mt-3 pt-2 border-t border-dashed border-border-color" x-show="showCols.status">
                        <div class="flex items-center gap-2">
                            @can('technology-edit-status')
                            <button @click="toggleStatus(tech.id)" class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none" :class="tech.status ? 'bg-green-500' : 'bg-gray-300'">
                                <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform shadow-sm" :class="tech.status ? 'translate-x-4' : 'translate-x-0.5'"></span>
                            </button>
                            @else
                            <span class="w-2 h-2 rounded-full" :class="tech.status ? 'bg-green-500' : 'bg-gray-400'"></span>
                            @endcan
                            <span class="text-[10px] font-bold uppercase" :class="tech.status ? 'text-green-600' : 'text-gray-400'" x-text="tech.status ? '{{ __('messages.active') }}' : '{{ __('messages.inactive') }}'"></span>
                        </div>

                        {{-- Actions --}}
                        <div class="flex gap-2">
                            @can('technology-edit')
                            <button @click="openModal('edit', tech)" class="h-8 w-8 rounded-full flex items-center justify-center bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-100 active:scale-95 transition-transform"><i class="ri-pencil-fill"></i></button>
                            @endcan
                            @can('technology-delete')
                            <button @click="confirmDelete(tech.id)" class="h-8 w-8 rounded-full flex items-center justify-center bg-red-50 text-red-600 border border-red-100 hover:bg-red-100 active:scale-95 transition-transform"><i class="ri-delete-bin-line"></i></button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{-- No data --}}
    <div x-show="technologies.length === 0" class="text-center py-10 text-secondary bg-card-bg rounded-xl border border-dashed border-border-color">
        <i class="ri-search-2-line text-4xl mb-2 inline-block opacity-50"></i>
        <p>{{ __('messages.no_technologies_found') }}</p>
    </div>
</div>