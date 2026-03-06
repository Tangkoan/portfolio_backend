<div class="bg-card-bg rounded-xl shadow-custom border border-border-color overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-page-bg/50 border-b border-border-color text-text-color text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 w-4"><input type="checkbox" @change="toggleSelectAll()" x-model="selectAll" class="rounded border-input-border text-primary focus:ring-primary h-4 w-4"></th>
                    <th class="px-6 py-4 font-bold" x-show="showCols.image">Image</th>
                    <th class="px-6 py-4 font-bold cursor-pointer hover:text-primary transition-colors" @click="sort('name')">
                        <div class="flex items-center gap-1">Project Name <span x-show="sortBy === 'name'" class="text-primary"><i x-show="sortDir === 'asc'" class="ri-arrow-up-line"></i><i x-show="sortDir === 'desc'" class="ri-arrow-down-line"></i></span></div>
                    </th>
                    <th class="px-6 py-4 font-bold" x-show="showCols.subtitle">Subtitle</th>
                    <th class="px-6 py-4 font-bold" x-show="showCols.link">Link</th>
                    <th class="px-6 py-4 font-bold" x-show="showCols.status">Status</th>
                    <th class="px-6 py-4 font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-color">
                <template x-for="item in projects" :key="item.id">
                    <tr class="hover:bg-page-bg/30 transition-colors">
                        <td class="px-6 py-4"><input type="checkbox" :value="item.id" x-model="selectedIds" class="rounded border-input-border text-primary focus:ring-primary h-4 w-4"></td>
                        
                        <td class="px-6 py-4" x-show="showCols.image">
                            <div class="h-12 w-16 rounded-lg bg-gray-100 overflow-hidden border border-border-color">
                                <template x-if="item.image"><img :src="'/storage/' + item.image" class="w-full h-full object-cover"></template>
                                <template x-if="!item.image"><div class="w-full h-full flex items-center justify-center text-secondary"><i class="ri-image-line"></i></div></template>
                            </div>
                        </td>

                        <td class="px-6 py-4 font-bold text-text-color" x-text="item.name"></td>
                        <td class="px-6 py-4 text-secondary text-sm" x-show="showCols.subtitle" x-text="item.sup_name || '-'"></td>
                        
                        <td class="px-6 py-4" x-show="showCols.link">
                            <template x-if="item.url_project">
                                <a :href="item.url_project" target="_blank" class="text-blue-500 hover:text-blue-700 hover:underline flex items-center gap-1 text-sm"><i class="ri-external-link-line"></i> Visit</a>
                            </template>
                            <template x-if="!item.url_project"><span class="text-secondary text-sm">-</span></template>
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
                <tr x-show="projects.length === 0">
                    <td colspan="7" class="px-6 py-12 text-center text-secondary"><i class="ri-macbook-line text-4xl mb-2 inline-block"></i><p>No projects found</p></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>