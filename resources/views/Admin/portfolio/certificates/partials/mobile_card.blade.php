<div class="flex flex-col gap-3">

    {{-- Select All Row (សម្រាប់រើសទិន្នន័យទាំងអស់) --}}
    <div class="flex items-center justify-between px-2" x-show="items.length > 0">
        <label class="flex items-center gap-2 text-sm font-bold text-text-color select-none cursor-pointer">
            <input type="checkbox" @change="toggleSelectAll()" x-model="selectAll" class="rounded border-input-border text-primary focus:ring-primary h-5 w-5 transition-colors">
            <span>ជ្រើសរើសទាំងអស់ (Select All)</span>
        </label>
        <span class="text-xs text-secondary font-medium"><span x-text="items.length"></span> Items</span>
    </div>

    {{-- Certificate Cards List --}}
    <template x-for="item in items" :key="'mobile-' + item.id">
        <div class="bg-card-bg p-4 rounded-2xl shadow-sm border border-border-color relative overflow-hidden transition-all duration-200"
             :class="{'ring-2 ring-primary bg-primary/5': selectedIds.includes(item.id)}">
            
            {{-- Checkbox សម្រាប់ Item នីមួយៗ --}}
            <input type="checkbox" :value="item.id" x-model="selectedIds" 
                   class="absolute top-4 left-4 z-20 rounded-md border-gray-300 text-primary focus:ring-primary h-5 w-5 shadow-sm bg-white">

            <div class="flex flex-col gap-3 pl-8"> 
                
                {{-- ផ្នែកបង្ហាញរូបភាព និង ព័ត៌មាន --}}
                <div class="flex gap-4">
                    {{-- Certificate Image Thumbnail --}}
                    <div class="relative shrink-0" x-show="showCols.image">
                        <div class="h-20 w-28 rounded-lg bg-gray-100 overflow-hidden border border-border-color cursor-pointer shadow-sm active:scale-95 transition-transform" 
                             @click="window.open('/storage/' + item.image, '_blank')" title="Click to view full image">
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

                    {{-- Certificate Info (ID & Date) --}}
                    <div class="flex-1 min-w-0 flex flex-col justify-center">
                        <h3 class="font-bold text-text-color text-sm truncate">Certificate #<span x-text="item.id"></span></h3>
                        <p class="text-xs text-secondary mt-1 flex items-center gap-1.5" x-show="showCols.date">
                            <i class="ri-calendar-line"></i> 
                            <span x-text="new Date(item.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })"></span>
                        </p>
                    </div>
                </div>

                {{-- បន្ទាត់ខណ្ឌ (Divider) --}}
                <div class="flex items-center justify-between mt-1 pt-3 border-t border-dashed border-border-color">
                    
                    {{-- Status Toggle Button --}}
                    <div class="flex items-center gap-2" x-show="showCols.status">
                        <button @click="toggleStatus(item.id)" class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none" :class="item.status ? 'bg-green-500' : 'bg-gray-300'">
                            <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform shadow-sm" :class="item.status ? 'translate-x-4' : 'translate-x-0.5'"></span>
                        </button>
                        <span class="text-[10px] font-bold uppercase tracking-wider" :class="item.status ? 'text-green-600' : 'text-gray-400'" x-text="item.status ? 'Published' : 'Hidden'"></span>
                    </div>

                    {{-- Action Buttons (Edit & Delete) --}}
                    <div class="flex gap-2">
                        <button @click="openModal('edit', item)" class="h-8 w-8 rounded-full flex items-center justify-center bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-100 active:scale-95 transition-transform shadow-sm">
                            <i class="ri-pencil-fill"></i>
                        </button>
                        <button @click="openDeleteModal('single', item.id)" class="h-8 w-8 rounded-full flex items-center justify-center bg-red-50 text-red-600 border border-red-100 hover:bg-red-100 active:scale-95 transition-transform shadow-sm">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </template>

    {{-- បង្ហាញនៅពេលគ្មានទិន្នន័យ (Empty State) --}}
    <div x-show="items.length === 0" class="text-center py-10 text-secondary bg-card-bg rounded-xl border border-dashed border-border-color" style="display: none;">
        <i class="ri-article-line text-4xl mb-2 inline-block opacity-50"></i>
        <p class="text-sm font-medium">មិនមានទិន្នន័យ (No certificates found)</p>
    </div>

</div>