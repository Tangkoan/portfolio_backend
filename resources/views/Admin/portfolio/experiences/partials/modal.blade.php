<div x-show="isModalOpen" style="display: none;" 
     class="fixed inset-0 z-[100] flex items-center justify-center px-4" x-cloak>

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeModal()"></div>

    {{-- Modal Content --}}
    <div class="relative w-full max-w-2xl bg-card-bg rounded-2xl shadow-2xl border border-border-color overflow-hidden flex flex-col max-h-[90vh]"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0">

        {{-- Header --}}
        <div class="px-6 py-4 border-b border-border-color flex justify-between items-center bg-page-bg/30">
            <h3 class="text-lg font-bold text-text-color"
                x-text="editMode ? 'Edit Experience' : 'Create Experience'"></h3>
            <button @click="closeModal()" class="text-secondary hover:text-text-color">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>

        {{-- Body (Form) --}}
        <form id="experienceForm" @submit.prevent="submitForm" class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar">

            {{-- Position & Company Row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-text-color mb-1">Position / Job Title</label>
                    <input type="text" x-model="form.name" class="w-full px-4 py-2.5 rounded-lg border border-input-border bg-input-bg text-text-color focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    <p x-show="errors.name" x-text="errors.name" class="text-red-500 text-xs mt-1"></p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-text-color mb-1">Company Name</label>
                    <input type="text" x-model="form.sup_name" class="w-full px-4 py-2.5 rounded-lg border border-input-border bg-input-bg text-text-color focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    <p x-show="errors.sup_name" x-text="errors.sup_name" class="text-red-500 text-xs mt-1"></p>
                </div>
            </div>

            {{-- Dates Row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-text-color mb-1">Start Date</label>
                    <input type="date" x-model="form.start_day" class="w-full px-4 py-2.5 rounded-lg border border-input-border bg-input-bg text-text-color focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    <p x-show="errors.start_day" x-text="errors.start_day" class="text-red-500 text-xs mt-1"></p>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-text-color mb-1">End Date <span class="text-secondary font-normal">(Leave blank if present)</span></label>
                    <input type="date" x-model="form.end_day" class="w-full px-4 py-2.5 rounded-lg border border-input-border bg-input-bg text-text-color focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    <p x-show="errors.end_day" x-text="errors.end_day" class="text-red-500 text-xs mt-1"></p>
                </div>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-bold text-text-color mb-1">Status</label>
                <select x-model="form.status" class="w-full px-4 py-2.5 rounded-lg border border-input-border bg-input-bg text-text-color focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

        </form>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-border-color bg-page-bg/30 flex flex-col-reverse sm:flex-row justify-end gap-3">
            
            <button x-show="selectedIds.length > 0 && editMode" 
                    type="button" @click="skipEdit()" 
                    class="px-4 py-2.5 bg-amber-50 text-amber-700 rounded-lg border border-amber-200 hover:bg-amber-100 flex items-center justify-center gap-2">
                <i class="ri-skip-forward-line"></i> Skip
            </button>

            <button type="button" @click="closeModal()" 
                    class="px-4 py-2.5 bg-white text-gray-700 rounded-lg border border-gray-300 hover:bg-gray-50 flex items-center justify-center gap-2">
                Cancel
            </button>

            <button type="button" @click="submitForm()" :disabled="isLoading"
                    class="px-6 py-2.5 bg-primary text-white rounded-lg border border-transparent hover:bg-primary/90 flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                <i x-show="isLoading" class="ri-loader-4-line animate-spin"></i>
                <i x-show="!isLoading" class="ri-save-3-line"></i>
                <span x-text="isLoading ? 'Saving...' : 'Save'"></span>
            </button>

        </div>

    </div>
</div>