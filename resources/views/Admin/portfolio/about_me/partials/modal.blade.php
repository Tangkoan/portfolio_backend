<div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center px-4" x-cloak>
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeModal()"></div>
    <div class="relative w-full max-w-2xl bg-card-bg rounded-2xl shadow-2xl border border-border-color overflow-hidden flex flex-col max-h-[90vh]"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
        
        <div class="px-6 py-4 border-b border-border-color flex justify-between items-center bg-page-bg/30">
            <h3 class="text-lg font-bold text-text-color" x-text="editMode ? 'Edit Profile' : 'Create Profile'"></h3>
            <button @click="closeModal()" class="text-secondary hover:text-text-color"><i class="ri-close-line text-xl"></i></button>
        </div>

        <form @submit.prevent="submitForm" class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- ផ្នែក Upload រូបភាព (ខាងឆ្វេង) --}}
                <div class="col-span-1 flex flex-col items-center">
                    <label class="block text-sm font-bold text-text-color mb-2 text-center w-full">Profile Image</label>
                    <div class="h-40 w-40 rounded-full bg-gray-100 border-2 border-dashed border-border-color overflow-hidden mb-3 relative group">
                        <template x-if="imagePreview">
                            <img :src="imagePreview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!imagePreview">
                            <div class="flex items-center justify-center w-full h-full text-secondary">
                                <i class="ri-user-add-line text-4xl"></i>
                            </div>
                        </template>
                        
                        {{-- Overlay ប៊ូតុង Upload --}}
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer" onclick="document.getElementById('profile_image_input').click()">
                            <span class="text-white text-sm font-medium"><i class="ri-upload-2-line mr-1"></i> Upload</span>
                        </div>
                    </div>
                    <input type="file" id="profile_image_input" @change="handleFileUpload" accept="image/*" class="hidden">
                    <p x-show="errors.image" x-text="errors.image" class="text-red-500 text-xs mt-1 text-center"></p>
                </div>

                {{-- ផ្នែកបញ្ចូលព័ត៌មាន (ខាងស្តាំ) --}}
                <div class="col-span-1 md:col-span-2 space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-text-color mb-1">Name / Title *</label>
                        <input type="text" x-model="form.name" placeholder="E.g. Full Name or Position" class="w-full px-4 py-2.5 rounded-lg border border-input-border bg-input-bg text-text-color focus:ring-primary outline-none">
                        <p x-show="errors.name" x-text="errors.name" class="text-red-500 text-xs mt-1"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-text-color mb-1">Description (About Me)</label>
                        <textarea x-model="form.description" rows="5" placeholder="Write something about yourself..." class="w-full px-4 py-2.5 rounded-lg border border-input-border bg-input-bg text-text-color focus:ring-primary outline-none custom-scrollbar"></textarea>
                        <p x-show="errors.description" x-text="errors.description" class="text-red-500 text-xs mt-1"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-text-color mb-1">Status</label>
                        <select x-model="form.status" class="w-full px-4 py-2.5 rounded-lg border border-input-border bg-input-bg text-text-color focus:ring-primary outline-none">
                            <option value="1">Active</option><option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

        </form>

        <div class="px-6 py-4 border-t border-border-color bg-page-bg/30 flex flex-col-reverse sm:flex-row justify-end gap-3">
            <button x-show="selectedIds.length > 0 && editMode" type="button" @click="skipEdit()" class="px-4 py-2.5 bg-amber-50 text-amber-700 rounded-lg border border-amber-200 hover:bg-amber-100 flex items-center justify-center gap-2"><i class="ri-skip-forward-line"></i> Skip</button>
            <button type="button" @click="closeModal()" class="px-4 py-2.5 bg-white text-gray-700 rounded-lg border border-gray-300 hover:bg-gray-50 flex items-center justify-center gap-2">Cancel</button>
            <button type="button" @click="submitForm()" :disabled="isLoading" class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary/90 flex items-center justify-center gap-2 disabled:opacity-70">
                <i x-show="isLoading" class="ri-loader-4-line animate-spin"></i><i x-show="!isLoading" class="ri-save-3-line"></i><span x-text="isLoading ? 'Saving...' : 'Save'"></span>
            </button>
        </div>
    </div>
</div>