<div class="bg-card-bg rounded-xl shadow-custom border border-border-color overflow-hidden">
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">

            <thead>
                <tr class="bg-page-bg/50 border-b border-border-color text-text-color text-sm uppercase tracking-wider">
                    {{-- Checkbox --}}
                    <th class="px-6 py-4 w-4">
                        <input type="checkbox"
                               @change="toggleSelectAll()"
                               x-model="selectAll"
                               class="rounded border-input-border text-primary focus:ring-primary h-4 w-4">
                    </th>

                    {{-- Position --}}
                    <th class="px-6 py-4 font-bold cursor-pointer hover:text-primary transition-colors"
                        @click="sort('name')">
                        <div class="flex items-center gap-1">
                            Position
                            <span x-show="sortBy === 'name'" class="text-primary">
                                <i x-show="sortDir === 'asc'" class="ri-arrow-up-line"></i>
                                <i x-show="sortDir === 'desc'" class="ri-arrow-down-line"></i>
                            </span>
                        </div>
                    </th>

                    {{-- Company --}}
                    <th class="px-6 py-4 font-bold cursor-pointer hover:text-primary transition-colors"
                        x-show="showCols.company" @click="sort('sup_name')">
                        <div class="flex items-center gap-1">
                            Company
                            <span x-show="sortBy === 'sup_name'" class="text-primary">
                                <i x-show="sortDir === 'asc'" class="ri-arrow-up-line"></i>
                                <i x-show="sortDir === 'desc'" class="ri-arrow-down-line"></i>
                            </span>
                        </div>
                    </th>

                    {{-- Duration --}}
                    <th class="px-6 py-4 font-bold cursor-pointer hover:text-primary transition-colors"
                        x-show="showCols.duration" @click="sort('start_day')">
                        <div class="flex items-center gap-1">
                            Duration
                            <span x-show="sortBy === 'start_day'" class="text-primary">
                                <i x-show="sortDir === 'asc'" class="ri-arrow-up-line"></i>
                                <i x-show="sortDir === 'desc'" class="ri-arrow-down-line"></i>
                            </span>
                        </div>
                    </th>

                    {{-- Status --}}
                    <th class="px-6 py-4 font-bold" x-show="showCols.status">
                        Status
                    </th>

                    {{-- Actions --}}
                    <th class="px-6 py-4 font-bold text-right">
                        Actions
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-border-color">
                <template x-for="item in experiences" :key="item.id">
                    <tr class="hover:bg-page-bg/30 transition-colors">
                        
                        {{-- Checkbox --}}
                        <td class="px-6 py-4">
                            <input type="checkbox"
                                   :value="item.id"
                                   x-model="selectedIds"
                                   class="rounded border-input-border text-primary focus:ring-primary h-4 w-4">
                        </td>

                        {{-- Position --}}
                        <td class="px-6 py-4 font-bold text-text-color"
                            x-text="item.name">
                        </td>

                        {{-- Company --}}
                        <td class="px-6 py-4 text-text-color"
                            x-show="showCols.company"
                            x-text="item.sup_name">
                        </td>

                        {{-- Duration --}}
                        <td class="px-6 py-4 text-secondary text-sm"
                            x-show="showCols.duration">
                            <span x-text="item.start_day"></span> - 
                            <span x-text="item.end_day ? item.end_day : 'Present'" :class="!item.end_day && 'text-green-600 font-bold'"></span>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4" x-show="showCols.status">
                            <button @click="toggleStatus(item.id)"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                                    :class="item.status ? 'bg-green-500' : 'bg-gray-300'">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                                      :class="item.status ? 'translate-x-6' : 'translate-x-1'">
                                </span>
                            </button>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button @click="openModal('edit', item)"
                                        class="h-8 w-8 rounded-lg flex items-center justify-center transition-colors bg-blue-50 text-blue-600 hover:bg-blue-100">
                                    <i class="ri-pencil-line"></i>
                                </button>
                                <button @click="confirmDelete(item.id)"
                                        class="h-8 w-8 rounded-lg flex items-center justify-center transition-colors bg-red-50 text-red-600 hover:bg-red-100">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>

                    </tr>
                </template>

                {{-- Empty State --}}
                <tr x-show="experiences.length === 0">
                    <td colspan="6" class="px-6 py-12 text-center text-secondary">
                        <i class="ri-briefcase-4-line text-4xl mb-2 inline-block"></i>
                        <p>No experiences found</p>
                    </td>
                </tr>
            </tbody>

        </table>
    </div>

</div>