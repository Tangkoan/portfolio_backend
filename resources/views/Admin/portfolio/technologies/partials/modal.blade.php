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
                x-text="editMode ? '{{ __('messages.edit_technology') }}' : '{{ __('messages.create_technology') }}'"></h3>

            <button type="button" @click="closeModal()" class="text-secondary hover:text-text-color">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>


        {{-- Body --}}
        <form @submit.prevent="submitForm" class="p-6 space-y-4">

            {{-- Technology Name --}}
            <div>
                <label class="block text-sm font-bold text-text-color mb-1">
                    {{ __('messages.technology_name') }}
                </label>

                <input type="text"
                       x-model="form.name"
                       class="w-full px-4 py-2.5 rounded-lg border border-input-border bg-input-bg text-text-color focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">

                <p x-show="errors.name"
                   x-text="errors.name"
                   class="text-red-500 text-xs mt-1"></p>
            </div>


            {{-- Image Upload --}}
            <div>
                <label class="block text-sm font-bold text-text-color mb-1">
                    {{ __('messages.image') }}
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
                           class="text-sm text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                </div>
            </div>


            {{-- Status --}}
            <div>
                <label class="block text-sm font-bold text-text-color mb-2">
                    {{ __('messages.status') }}
                </label>

                <select x-model="form.status"
                        class="w-full px-4 py-2.5 rounded-lg border border-input-border bg-input-bg text-text-color focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">

                    <option value="1">{{ __('messages.status_active') }}</option>
                    <option value="0">{{ __('messages.status_inactive') }}</option>

                </select>
            </div>

        </form>


        {{-- Footer --}}
        <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 p-6 pt-4 border-t border-border-color bg-card-bg">
            
            {{-- Skip Button --}}
            <button 
                x-show="selectedIds.length > 0 && editMode" 
                type="button" 
                @click="skipEdit()" 
                class="mt-3 sm:mt-0 inline-flex items-center justify-center px-4 py-2.5 bg-amber-50 border border-amber-200 rounded-lg text-sm font-medium text-amber-700 hover:bg-amber-100 hover:text-amber-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all duration-200 w-full sm:w-auto"
            >
                <i class="ri-skip-forward-line mr-2"></i>
                {{ __('messages.btn_skip') }}
            </button>

            {{-- Cancel Button --}}
            <button 
                type="button" 
                @click="closeModal()" 
                class="mt-3 sm:mt-0 inline-flex items-center justify-center px-4 py-2.5 bg-page-bg border border-input-border rounded-lg text-sm font-medium text-text-color hover:bg-input-bg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 w-full sm:w-auto"
            >
                <i class="ri-close-line mr-2"></i>
                {{ __('messages.btn_cancel') }}
            </button>

            {{-- Save Button --}}
            <button 
                type="button" 
                @click="submitForm()" 
                :disabled="isLoading"
                class="inline-flex items-center justify-center px-4 py-2.5 bg-primary border border-transparent rounded-lg text-sm font-medium text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200 w-full sm:w-auto disabled:opacity-70 disabled:cursor-not-allowed shadow-sm"
            >
                <i x-show="isLoading" class="ri-loader-4-line animate-spin mr-2"></i>
                <i x-show="!isLoading" class="ri-save-3-line mr-2"></i>
                <span x-text="isLoading ? '{{ __('messages.btn_saving') }}' : (editMode ? '{{ __('messages.update') }}' : '{{ __('messages.save') }}')"></span>
            </button>

        </div>

    </div>
</div>