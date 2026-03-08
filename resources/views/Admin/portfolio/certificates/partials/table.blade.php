<div class="bg-card-bg rounded-xl shadow-custom border border-border-color overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-page-bg/50 border-b border-border-color text-text-color text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 w-4">
                        <input type="checkbox" @change="toggleSelectAll()" x-model="selectAll" 
                               @cannot('certificates-delete') disabled @endcannot
                               class="rounded border-input-border text-primary focus:ring-primary h-4 w-4 disabled:opacity-50 disabled:cursor-not-allowed">
                    </th>
                    <th class="px-6 py-4 font-bold">{{ __('messages.certificate_image') }}</th>
                    <th class="px-6 py-4 font-bold cursor-pointer select-none hover:text-primary transition-colors" @click="sort('created_at')">
                        <div class="flex items-center gap-1">{{ __('messages.upload_date') }} 
                            <span x-show="sortBy === 'created_at'" class="text-primary" x-cloak>
                                <i x-show="sortDir === 'asc'" class="ri-arrow-up-line"></i>
                                <i x-show="sortDir === 'desc'" class="ri-arrow-down-line"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-6 py-4 font-bold text-center">{{ __('messages.status') }}</th>
                    <th class="px-6 py-4 font-bold text-right">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-color">
                <template x-for="item in items" :key="item.id">
                    <tr class="hover:bg-page-bg/30 transition-colors">
                        <td class="px-6 py-4">
                            <input type="checkbox" :value="item.id" x-model="selectedIds" 
                                   @cannot('certificates-delete') disabled @endcannot
                                   class="rounded border-input-border text-primary focus:ring-primary h-4 w-4 disabled:opacity-50 disabled:cursor-not-allowed">
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="h-20 w-32 rounded-lg bg-gray-100 overflow-hidden border border-border-color cursor-pointer hover:scale-110 origin-left transition-transform" 
                                 @click="window.open('/storage/' + item.image, '_blank')" title="{{ __('messages.click_to_view') }}">
                                <template x-if="item.image"><img :src="'/storage/' + item.image" class="w-full h-full object-cover"></template>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-secondary text-sm" x-text="new Date(item.created_at).toLocaleDateString()"></td>
                        
                        <td class="px-6 py-4 text-center">
                            <button 
                                type="button"
                                @can('certificates-edit-status')
                                    @click="toggleStatus(item.id)" 
                                @else
                                    disabled
                                @endcan
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed" 
                                :class="item.status ? 'bg-green-500' : 'bg-gray-300'"
                            >
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform" :class="item.status ? 'translate-x-6' : 'translate-x-1'"></span>
                            </button>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button 
                                    type="button"
                                    @can('certificates-edit')
                                        @click="openModal('edit', item)" 
                                    @else
                                        disabled
                                    @endcan
                                    class="h-8 w-8 rounded-lg flex items-center justify-center transition-colors bg-blue-50 text-blue-600 hover:bg-blue-100 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-blue-50"
                                >
                                    <i class="ri-pencil-line"></i>
                                </button>
                                <button 
                                    type="button"
                                    @can('certificates-delete')
                                        @click="openDeleteModal('single', item.id)" 
                                    @else
                                        disabled
                                    @endcan
                                    class="h-8 w-8 rounded-lg flex items-center justify-center transition-colors bg-red-50 text-red-600 hover:bg-red-100 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-red-50"
                                >
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
                <tr x-show="items?.length === 0" x-cloak>
                    <td colspan="5" class="px-6 py-12 text-center text-secondary"><i class="ri-article-line text-4xl mb-2 inline-block"></i><p>{{ __('messages.no_certificates_found') }}</p></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>