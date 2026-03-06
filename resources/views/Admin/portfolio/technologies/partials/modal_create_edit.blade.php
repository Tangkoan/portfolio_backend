<div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center px-4" x-cloak>
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeModal()"></div>

    <div class="relative w-full max-w-xl bg-card-bg rounded-2xl shadow-2xl border border-border-color overflow-hidden flex flex-col max-h-[90vh]"
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0 scale-95 translate-y-4" 
         x-transition:enter-end="opacity-100 scale-100 translate-y-0">

        {{-- Header --}}
        <div class="px-6 py-4 border-b border-border-color flex justify-between items-center bg-page-bg/30">
            <h3 class="text-lg font-bold text-text-color" x-text="editMode ? 'Edit Technology' : 'Create Technology'"></h3>
            <button @click="closeModal()" class="text-secondary hover:text-text-color">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>

        {{-- Form --}}
        <form @submit.prevent="submitForm" class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar">

            {{-- Technology Name --}}
            <div>
                <label class="block text-sm font-bold text-text-color mb-1">Technology Name</label>
                <input type="text" x-model="form.name" class="w-full px-4 py-2.5 rounded-lg border border-input-border bg-input-bg text-text-color focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                <p x-show="errors.name" x-text="errors.name" class="text-red-500 text-xs mt-1"></p>
            </div>

            {{-- Image --}}
            <div>
                <label class="block text-sm font-bold text-text-color mb-1">Image</label>
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-lg bg-gray-100 border border-border-color overflow-hidden flex-shrink-0">
                        <template x-if="imagePreview">
                            <img :src="imagePreview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!imagePreview">
                            <div class="w-full h-full flex items-center justify-center text-secondary">
                                <i class="ri-image-add-line"></i>
                            </div>
                        </template>
                    </div>
                    <input type="file" @change="handleFileUpload" accept="image/*" class="text-sm text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                </div>
            </div>

        </form>

        {{-- Footer --}}
        <div class="p-6 flex justify-end gap-3 border-t border-border-color bg-card-bg">
            <button type="button" @click="closeModal()" class="px-4 py-2 rounded-lg border border-input-border text-text-color hover:bg-page-bg transition">
                Cancel
            </button>
            <button type="button" @click="submitForm" class="bg-primary text-white px-6 py-2 rounded-lg hover:opacity-90 transition flex items-center gap-2" :disabled="isLoading">
                <i x-show="isLoading" class="ri-loader-4-line animate-spin"></i>
                <span x-text="editMode ? 'Update' : 'Save'"></span>
            </button>
        </div>

    </div>
</div>