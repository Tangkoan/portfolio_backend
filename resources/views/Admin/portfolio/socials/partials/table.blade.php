<div class="bg-card-bg rounded-xl shadow-custom border border-border-color overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-page-bg/50 border-b border-border-color text-text-color text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 w-4"><input type="checkbox" @change="toggleSelectAll()" x-model="selectAll" class="rounded border-input-border text-primary focus:ring-primary h-4 w-4"></th>
                    <th class="px-6 py-4 font-bold w-20" x-show="showCols.image">Icon</th>
                    <th class="px-6 py-4 font-bold cursor-pointer hover:text-primary transition-colors" @click="sort('name')">
                        <div class="flex items-center gap-1">Platform <span x-show="sortBy === 'name'" class="text-primary"><i x-show="sortDir === 'asc'" class="ri-arrow-up-line"></i><i x-show="sortDir === 'desc'" class="ri-arrow-down-line"></i></span></div>
                    </th>
                    <th class="px-6 py-4 font-bold" x-show="showCols.link">URL</th>
                    <th class="px-6 py-4 font-bold" x-show="showCols.status">Status</th>
                    <th class="px-6 py-4 font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-color">
                <template x-for="item in socials" :key="item.id">
                    <tr class="hover:bg-page-bg/30 transition-colors">
                        <td class="px-6 py-4"><input type="checkbox" :value="item.id" x-model="selectedIds" class="rounded border-input-border text-primary focus:ring-primary h-4 w-4"></td>
                        
                        <td class="px-6 py-4" x-show="showCols.image">
                            <div class="h-10 w-10 rounded-lg bg-gray-100 overflow-hidden border border-border-color">
                                <template x-if="item.image"><img :src="'/storage/' + item.image" class="w-full h-full object-cover p-1"></template>
                                <template x-if="!item.image"><div class="w-full h-full flex items-center justify-center text-secondary"><i class="ri-links-line"></i></div></template>
                            </div>
                        </td>

                        <td class="px-6 py-4 font-bold text-text-color" x-text="item.name"></td>
                        
                        <td class="px-6 py-4" x-show="showCols.link">
                            <a :href="item.url_social" target="_blank" class="text-blue-500 hover:text-blue-700 hover:underline flex items-center gap-1 text-sm truncate max-w-xs"><i class="ri-external-link-line shrink-0"></i> <span x-text="item.url_social"></span></a>
                        </td>

                        <td class="px-6 py-4" x-show="showCols.status">
                            <button @click="toggleStatus(item.id)" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors" :class="item.status ? 'bg-green-500' : 'bg-gray-300'">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform" :class="item.status ? 'translate-x-6' : 'translate-x-1'"></span>
                            </button>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button @click="openModal('edit', item)" class="h-8 w-8 rounded-lg flex items-center justify-center transition-colors bg-blue-50 text-blue-600 hover:bg-blue-100"><i class="ri-pencil-line"></i></button>
                                <button @click="openDeleteModal('single', item.id)" class="h-8 w-8 rounded-lg flex items-center justify-center transition-colors bg-red-50 text-red-600 hover:bg-red-100"><i class="ri-delete-bin-line"></i></button>
                            </div>
                        </td>
                    </tr>
                </template>
                <tr x-show="socials.length === 0">
                    <td colspan="6" class="px-6 py-12 text-center text-secondary"><i class="ri-links-line text-4xl mb-2 inline-block"></i><p>No social links found</p></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>