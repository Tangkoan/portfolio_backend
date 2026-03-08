<div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center px-4" x-cloak>
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeModal()"></div>
    <div class="relative w-full max-w-lg bg-card-bg rounded-2xl shadow-2xl border border-border-color overflow-hidden flex flex-col"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
        
        <div class="px-6 py-4 border-b border-border-color flex justify-between items-center bg-page-bg/30">
            <h3 class="text-lg font-bold text-text-color" x-text="editMode ? '{{ __('messages.edit_certificate') }}' : '{{ __('messages.upload_certificate') }}'"></h3>
            <button type="button" @click="closeModal()" class="text-secondary hover:text-text-color"><i class="ri-close-line text-xl"></i></button>
        </div>

        <form @submit.prevent="submitForm" class="p-6 space-y-6">
            
            <div class="flex flex-col items-center justify-center w-full">
                <label for="cert_image_input" class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed rounded-xl cursor-pointer bg-input-bg border-input-border hover:bg-page-bg/50 transition-colors relative overflow-hidden group">
                    
                    <template x-if="!imagePreview">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i class="ri-cloud-upload-line text-3xl text-secondary mb-2"></i>
                            <p class="mb-1 text-sm text-secondary"><span class="font-bold">{{ __('messages.click_to_upload') }}</span> {{ __('messages.drag_drop') }}</p>
                            <p class="text-xs text-secondary opacity-70">{{ __('messages.img_format_size') }}</p>
                        </div>
                    </template>
                    
                    <template x-if="imagePreview">
                        <img :src="imagePreview" class="w-full h-full object-contain p-2">
                    </template>
                    
                    <div x-show="imagePreview" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-white text-sm font-medium"><i class="ri-loop-right-line mr-1"></i> {{ __('messages.change_image') }}</span>
                    </div>

                    <input id="cert_image_input" type="file" @change="handleFileUpload" accept="image/*" class="hidden">
                </label>
                <p x-show="errors.image" x-text="errors.image" class="text-red-500 text-xs mt-2 w-full text-left"></p>
            </div>

            <div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" x-model="form.status" :true-value="1" :false-value="0" class="sr-only">
                        <div class="block bg-gray-300 w-12 h-6 rounded-full transition-colors" :class="form.status ? 'bg-green-500' : 'bg-gray-300'"></div>
                        <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform" :class="form.status ? 'transform translate-x-6' : ''"></div>
                    </div>
                    <span class="text-sm font-bold text-text-color" x-text="form.status ? '{{ __('messages.published_active') }}' : '{{ __('messages.hidden_draft') }}'"></span>
                </label>
            </div>

        </form>

        <div class="px-6 py-4 border-t border-border-color bg-page-bg/30 flex flex-col-reverse sm:flex-row justify-end gap-3">
            <button x-show="selectedIds.length > 0 && editMode" type="button" @click="skipEdit()" class="px-4 py-2.5 bg-amber-50 text-amber-700 rounded-lg border border-amber-200 hover:bg-amber-100 flex items-center justify-center gap-2"><i class="ri-skip-forward-line"></i> {{ __('messages.btn_skip') }}</button>
            <button type="button" @click="closeModal()" class="px-4 py-2.5 bg-white text-gray-700 rounded-lg border border-gray-300 hover:bg-gray-50 flex items-center justify-center gap-2">{{ __('messages.btn_cancel') }}</button>
            <button type="button" @click="submitForm()" :disabled="isLoading" class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary/90 flex items-center justify-center gap-2 disabled:opacity-70">
                <i x-show="isLoading" class="ri-loader-4-line animate-spin"></i><i x-show="!isLoading" class="ri-save-3-line"></i>
                <span x-text="isLoading ? '{{ __('messages.uploading') }}' : '{{ __('messages.save') }}'"></span>
            </button>
        </div>
    </div>
</div>