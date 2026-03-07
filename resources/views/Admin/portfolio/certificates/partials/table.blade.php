<div class="bg-card-bg rounded-xl shadow-custom border border-border-color overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-page-bg/50 border-b border-border-color text-text-color text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 w-4"><input type="checkbox" @change="toggleSelectAll()" x-model="selectAll" class="rounded border-input-border text-primary focus:ring-primary h-4 w-4"></th>
                    <th class="px-6 py-4 font-bold">Certificate Image</th>
                    <th class="px-6 py-4 font-bold cursor-pointer" @click="sort('created_at')">Upload Date</th>
                    <th class="px-6 py-4 font-bold text-center">Status</th>
                    <th class="px-6 py-4 font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-color">
                <template x-for="item in items" :key="item.id">
                    <tr class="hover:bg-page-bg/30 transition-colors">
                        <td class="px-6 py-4"><input type="checkbox" :value="item.id" x-model="selectedIds" class="rounded border-input-border text-primary focus:ring-primary h-4 w-4"></td>
                        
                        <td class="px-6 py-4">
                            <div class="h-20 w-32 rounded-lg bg-gray-100 overflow-hidden border border-border-color cursor-pointer hover:scale-110 origin-left transition-transform" @click="window.open('/storage/' + item.image, '_blank')">
                                <template x-if="item.image"><img :src="'/storage/' + item.image" class="w-full h-full object-cover"></template>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-secondary text-sm" x-text="new Date(item.created_at).toLocaleDateString()"></td>
                        
                        <td class="px-6 py-4 text-center">
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
                <tr x-show="items.length === 0">
                    <td colspan="5" class="px-6 py-12 text-center text-secondary"><i class="ri-article-line text-4xl mb-2 inline-block"></i><p>No certificates found</p></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>