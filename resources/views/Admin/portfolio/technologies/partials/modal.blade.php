<div x-show="isModalOpen" style="display: none;" 
     class="fixed inset-0 z-[100] flex items-center justify-center px-4" x-cloak>

    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeModal()"></div>

    <div class="relative w-full max-w-lg bg-card-bg rounded-2xl shadow-2xl border border-border-color overflow-hidden flex flex-col"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0">

        {{-- Header --}}
        <div class="px-6 py-4 border-b border-border-color flex justify-between items-center">
            <h3 class="text-lg font-bold text-text-color"
                x-text="editMode ? 'Edit Technology' : 'Create Technology'"></h3>

            <button @click="closeModal()" class="text-secondary hover:text-text-color">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>


        {{-- Body --}}
        <form @submit.prevent="submitForm" class="p-6 space-y-4">

            {{-- Technology Name --}}
            <div>
                <label class="block text-sm font-bold text-text-color mb-1">
                    Technology Name
                </label>

                <input type="text"
                       x-model="form.name"
                       class="w-full px-4 py-2.5 rounded-lg border border-input-border bg-input-bg text-text-color
                       focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">

                <p x-show="errors.name"
                   x-text="errors.name"
                   class="text-red-500 text-xs mt-1"></p>
            </div>


            {{-- Image Upload --}}
            <div>
                <label class="block text-sm font-bold text-text-color mb-1">
                    Image
                </label>

                <div class="flex items-center gap-4">

                    <div class="h-14 w-14 rounded-lg bg-gray-100 border border-border-color overflow-hidden">
                        <template x-if="imagePreview">
                            <img :src="imagePreview" class="w-full h-full object-cover">
                        </template>

                        <template x-if="!imagePreview">
                            <div class="flex items-center justify-center w-full h-full text-secondary">
                                <i class="ri-image-add-line"></i>
                            </div>
                        </template>
                    </div>

                    <input type="file"
                           @change="handleFileUpload"
                           accept="image/*"
                           class="text-sm text-secondary
                           file:mr-4 file:py-2 file:px-4
                           file:rounded-lg file:border-0
                           file:text-xs file:font-semibold
                           file:bg-primary/10 file:text-primary
                           hover:file:bg-primary/20">
                </div>
            </div>


            {{-- Status --}}
            <div>
                <label class="block text-sm font-bold text-text-color mb-2">
                    Status
                </label>

                <select x-model="form.status"
                        class="w-full px-4 py-2.5 rounded-lg border border-input-border bg-input-bg text-text-color
                        focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">

                    <option value="1">Active</option>
                    <option value="0">Inactive</option>

                </select>
            </div>

        </form>


        {{-- Footer --}}
        {{-- ផ្នែកខាងក្រោមនៃ Modal (Modal Footer) --}}
        <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 mt-6 pt-4 border-t border-gray-200">
            
            {{-- 1. ប៊ូតុង Skip (បង្ហាញតែពេលមាន Select ទិន្នន័យច្រើន និងកំពុង Edit) --}}
            <button 
                x-show="selectedIds.length > 0 && editMode" 
                type="button" 
                @click="skipEdit()" 
                class="mt-3 sm:mt-0 inline-flex items-center justify-center px-4 py-2.5 bg-amber-50 border border-amber-200 rounded-lg text-sm font-medium text-amber-700 hover:bg-amber-100 hover:text-amber-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all duration-200 w-full sm:w-auto"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                </svg>
                Skip
            </button>

            {{-- 2. ប៊ូតុង Cancel --}}
            <button 
                type="button" 
                @click="closeModal()" 
                class="mt-3 sm:mt-0 inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 w-full sm:w-auto"
            >
                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Cancel
            </button>

            {{-- 3. ប៊ូតុង Save (មានភ្ជាប់ជាមួយមុខងារ Loading) --}}
            <button 
                type="button" 
                @click="submitForm()" 
                :disabled="isLoading"
                class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 w-full sm:w-auto disabled:opacity-70 disabled:cursor-not-allowed shadow-sm"
            >
                {{-- សញ្ញាវិលៗ (Spinner) ពេលកំពុង Save --}}
                <svg x-show="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display: none;">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>

                {{-- Icon Save ធម្មតា (បង្ហាញពេលអត់មាន Loading) --}}
                <svg x-show="!isLoading" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>

                {{-- ដូរអក្សរពេលកំពុង Save --}}
                <span x-text="isLoading ? 'កំពុងរក្សាទុក...' : 'រក្សាទុក (Save)'"></span>
            </button>

        </div>

    </div>
</div>