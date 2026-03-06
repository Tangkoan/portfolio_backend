<div class="flex flex-col gap-3">

    {{-- Select All Row --}}
    <div class="flex items-center justify-between px-2" x-show="projects.length > 0">
        <label class="flex items-center gap-2 text-sm font-bold text-text-color select-none cursor-pointer">
            <input type="checkbox" @change="toggleSelectAll()" x-model="selectAll" class="rounded border-input-border text-primary focus:ring-primary h-5 w-5">
            <span>ជ្រើសរើសទាំងអស់ (Select All)</span>
        </label>
        <span class="text-xs text-secondary"><span x-text="projects.length"></span> Items</span>
    </div>

    {{-- Project List --}}
    <template x-for="item in projects" :key="'mobile-' + item.id">
        <div class="bg-card-bg p-4 rounded-2xl shadow-sm border border-border-color relative overflow-hidden transition-all duration-200"
             :class="{'ring-2 ring-primary bg-primary/5': selectedIds.includes(item.id)}">
            
            {{-- Checkbox --}}
            <input type="checkbox" :value="item.id" x-model="selectedIds" 
                   class="absolute top-4 left-4 z-20 rounded-md border-gray-300 text-primary focus:ring-primary h-5 w-5 shadow-sm bg-white">

            <div class="flex flex-col gap-3 pl-8"> 
                
                <div class="flex gap-3">
                    {{-- Image Area --}}
                    <div class="relative shrink-0" x-show="showCols.image">
                        <div class="h-16 w-20 rounded-xl bg-gray-100 overflow-hidden border border-border-color">
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

                    {{-- Content Area --}}
                    <div class="flex-1 min-w-0 flex flex-col justify-center">
                        {{-- Name --}}
                        <h3 class="font-bold text-text-color text-base truncate" x-text="item.name"></h3>
                        {{-- Subtitle --}}
                        <p class="text-sm text-secondary truncate mt-0.5" x-show="showCols.subtitle" x-text="item.sup_name || '-'"></p>
                    </div>
                </div>

                {{-- Link Area --}}
                <div x-show="showCols.link" class="mt-1">
                    <template x-if="item.url_project">
                        <a :href="item.url_project" target="_blank" class="text-blue-500 hover:text-blue-700 hover:underline flex items-center gap-1 text-sm bg-blue-50 px-3 py-1.5 rounded-lg inline-flex w-fit">
                            <i class="ri-external-link-line"></i> Visit Project
                        </a>
                    </template>
                </div>

                {{-- Bottom Actions & Status --}}
                <div class="flex items-center justify-between mt-2 pt-3 border-t border-dashed border-border-color">
                    
                    {{-- Status --}}
                    <div class="flex items-center gap-2" x-show="showCols.status">
                        <button @click="toggleStatus(item.id)" class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none" :class="item.status ? 'bg-green-500' : 'bg-gray-300'">
                            <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform shadow-sm" :class="item.status ? 'translate-x-4' : 'translate-x-0.5'"></span>
                        </button>
                        <span class="text-[10px] font-bold uppercase" :class="item.status ? 'text-green-600' : 'text-gray-400'" x-text="item.status ? 'Active' : 'Inactive'"></span>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2">
                        <button @click="openModal('edit', item)" class="h-8 w-8 rounded-full flex items-center justify-center bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-100 active:scale-95 transition-transform">
                            <i class="ri-pencil-fill"></i>
                        </button>
                        <button @click="openDeleteModal('single', item.id)" class="h-8 w-8 rounded-full flex items-center justify-center bg-red-50 text-red-600 border border-red-100 hover:bg-red-100 active:scale-95 transition-transform">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </template>

    {{-- No data --}}
    <div x-show="projects.length === 0" class="text-center py-10 text-secondary bg-card-bg rounded-xl border border-dashed border-border-color">
        <i class="ri-macbook-line text-4xl mb-2 inline-block opacity-50"></i>
        <p>មិនមានទិន្នន័យ (No projects found)</p>
    </div>

</div>