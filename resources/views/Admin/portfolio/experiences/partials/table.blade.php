<div class="bg-card-bg rounded-xl shadow-custom border border-border-color overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-page-bg/50 border-b border-border-color text-text-color text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 w-4">
                        <input type="checkbox" @change="toggleSelectAll()" x-model="selectAll" 
                               @cannot('experiences-delete') disabled @endcannot
                               class="rounded border-input-border text-primary focus:ring-primary h-4 w-4 disabled:opacity-50 disabled:cursor-not-allowed">
                    </th>
                    <th class="px-6 py-4 font-bold cursor-pointer hover:text-primary transition-colors select-none" @click="sort('name')">
                        <div class="flex items-center gap-1">{{ __('messages.position') }} 
                            <span x-show="sortBy === 'name'" class="text-primary" x-cloak>
                                <i x-show="sortDir === 'asc'" class="ri-arrow-up-line"></i>
                                <i x-show="sortDir === 'desc'" class="ri-arrow-down-line"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-6 py-4 font-bold cursor-pointer hover:text-primary transition-colors select-none" x-show="showCols.company" @click="sort('sup_name')">
                        <div class="flex items-center gap-1">{{ __('messages.company') }} 
                            <span x-show="sortBy === 'sup_name'" class="text-primary" x-cloak>
                                <i x-show="sortDir === 'asc'" class="ri-arrow-up-line"></i>
                                <i x-show="sortDir === 'desc'" class="ri-arrow-down-line"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-6 py-4 font-bold cursor-pointer hover:text-primary transition-colors select-none" x-show="showCols.duration" @click="sort('start_day')">
                        <div class="flex items-center gap-1">{{ __('messages.duration') }} 
                            <span x-show="sortBy === 'start_day'" class="text-primary" x-cloak>
                                <i x-show="sortDir === 'asc'" class="ri-arrow-up-line"></i>
                                <i x-show="sortDir === 'desc'" class="ri-arrow-down-line"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-6 py-4 font-bold" x-show="showCols.status">{{ __('messages.status') }}</th>
                    <th class="px-6 py-4 font-bold text-right">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-color">
                <template x-for="item in experiences" :key="item.id">
                    <tr class="hover:bg-page-bg/30 transition-colors">
                        <td class="px-6 py-4">
                            <input type="checkbox" :value="item.id" x-model="selectedIds" 
                                   @cannot('experiences-delete') disabled @endcannot
                                   class="rounded border-input-border text-primary focus:ring-primary h-4 w-4 disabled:opacity-50 disabled:cursor-not-allowed">
                        </td>
                        <td class="px-6 py-4 font-bold text-text-color" x-text="item.name"></td>
                        <td class="px-6 py-4 text-text-color" x-show="showCols.company" x-text="item.sup_name"></td>
                        <td class="px-6 py-4 text-secondary text-sm" x-show="showCols.duration">
                            <span x-text="item.start_day"></span> - 
                            <span x-text="item.end_day ? item.end_day : '{{ __('messages.present') }}'" :class="!item.end_day && 'text-green-600 font-bold'"></span>
                        </td>
                        <td class="px-6 py-4" x-show="showCols.status">
                            <button 
                                type="button" 
                                @can('experiences-edit-status')
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
                                    @can('experiences-edit')
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
                                    @can('experiences-delete')
                                        @click="confirmDelete(item.id)"
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
                <tr x-show="experiences?.length === 0" x-cloak>
                    <td colspan="6" class="px-6 py-12 text-center text-secondary"><i class="ri-briefcase-4-line text-4xl mb-2 inline-block"></i><p>{{ __('messages.no_experiences_found') }}</p></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>