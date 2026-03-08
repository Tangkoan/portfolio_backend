<div class="flex flex-col gap-3">

    {{-- Select All Row --}}
    <div class="flex items-center justify-between px-2" x-show="items?.length > 0" x-cloak>
        <label class="flex items-center gap-2 text-sm font-bold text-text-color select-none cursor-pointer">
            <input type="checkbox" @change="toggleSelectAll()" x-model="selectAll" 
                   @cannot('certificates-delete') disabled @endcannot
                   class="rounded border-input-border text-primary focus:ring-primary h-5 w-5 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
            <span :class="{'opacity-50': {{ auth()->user()->cannot('certificates-delete') ? 'true' : 'false' }}}">{{ __('messages.select_all') }}</span>
        </label>
        <span class="text-xs text-secondary font-medium"><span x-text="items?.length"></span> {{ __('messages.items') }}</span>
    </div>

    {{-- Certificate Cards List --}}
    <template x-for="item in items" :key="'mobile-' + item.id">
        <div class="bg-card-bg p-4 rounded-2xl shadow-sm border border-border-color relative overflow-hidden transition-all duration-200"
             :class="{'ring-2 ring-primary bg-primary/5': selectedIds.includes(item.id)}">
            
            <input type="checkbox" :value="item.id" x-model="selectedIds" 
                   @cannot('certificates-delete') disabled @endcannot
                   class="absolute top-4 left-4 z-20 rounded-md border-gray-300 text-primary focus:ring-primary h-5 w-5 shadow-sm bg-white disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-200">

            <div class="flex flex-col gap-3 pl-8"> 
                
                <div class="flex gap-4">
                    {{-- Certificate Image Thumbnail --}}
                    <div class="relative shrink-0" x-show="showCols.image">
                        <div class="h-20 w-28 rounded-lg bg-gray-100 overflow-hidden border border-border-color cursor-pointer shadow-sm active:scale-95 transition-transform" 
                             @click="window.open('/storage/' + item.image, '_blank')" title="{{ __('messages.click_to_view') }}">
                            <template x-if="item.image">
                                <img :src="'/storage/' + item.image" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!item.image">
                                <div class="w-full h-full flex items-center justify-center text-secondary">
                                    <i class="ri-image-line text-2xl"></i>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Certificate Info --}}
                    <div class="flex-1 min-w-0 flex flex-col justify-center">
                        <h3 class="font-bold text-text-color text-sm truncate">{{ __('messages.certificate') }} #<span x-text="item.id"></span></h3>
                        <p class="text-xs text-secondary mt-1 flex items-center gap-1.5" x-show="showCols.date">
                            <i class="ri-calendar-line"></i> 
                            <span x-text="new Date(item.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })"></span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-1 pt-3 border-t border-dashed border-border-color">
                    
                    {{-- Status Toggle --}}
                    <div class="flex items-center gap-2" x-show="showCols.status">
                        <button 
                            type="button"
                            @can('certificates-edit-status')
                                @click="toggleStatus(item.id)" 
                            @else
                                disabled
                            @endcan
                            class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed" 
                            :class="item.status ? 'bg-green-500' : 'bg-gray-300'"
                        >
                            <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform shadow-sm" :class="item.status ? 'translate-x-4' : 'translate-x-0.5'"></span>
                        </button>
                        <span class="text-[10px] font-bold uppercase tracking-wider" :class="item.status ? 'text-green-600' : 'text-gray-400'" 
                              x-text="item.status ? '{{ __('messages.status_published') }}' : '{{ __('messages.status_hidden') }}'"></span>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2">
                        <button 
                            type="button"
                            @can('certificates-edit')
                                @click="openModal('edit', item)" 
                            @else
                                disabled
                            @endcan
                            class="h-8 w-8 rounded-full flex items-center justify-center bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-100 active:scale-95 transition-transform shadow-sm disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-blue-50 disabled:active:scale-100"
                        >
                            <i class="ri-pencil-fill"></i>
                        </button>
                        <button 
                            type="button"
                            @can('certificates-delete')
                                @click="openDeleteModal('single', item.id)" 
                            @else
                                disabled
                            @endcan
                            class="h-8 w-8 rounded-full flex items-center justify-center bg-red-50 text-red-600 border border-red-100 hover:bg-red-100 active:scale-95 transition-transform shadow-sm disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-red-50 disabled:active:scale-100"
                        >
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </template>

    <div x-show="items?.length === 0" class="text-center py-10 text-secondary bg-card-bg rounded-xl border border-dashed border-border-color" x-cloak>
        <i class="ri-article-line text-4xl mb-2 inline-block opacity-50"></i>
        <p class="text-sm font-medium">{{ __('messages.no_certificates_found') }}</p>
    </div>

</div>