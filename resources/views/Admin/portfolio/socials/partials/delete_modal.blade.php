<div x-show="isDeleteModalOpen" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center px-4" x-cloak>
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeDeleteModal()"></div>
    <div class="relative w-full max-w-md bg-card-bg rounded-2xl shadow-2xl border border-border-color p-6 text-center" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 text-red-600 mb-4"><i class="ri-delete-bin-line text-3xl"></i></div>
        <h3 class="text-xl font-bold text-text-color mb-2">Confirm Deletion</h3>
        <p class="text-secondary mb-6">តើអ្នកពិតជាចង់លុប <strong x-show="deleteType === 'single'" class="text-red-500">តំណភ្ជាប់នេះ</strong><strong x-show="deleteType === 'bulk'" class="text-red-500"><span x-text="selectedIds.length"></span> តំណភ្ជាប់</strong> មែនទេ?</p>
        <div class="flex flex-col-reverse sm:flex-row justify-center gap-3">
            <button @click="closeDeleteModal()" type="button" class="px-6 py-2.5 rounded-xl border border-input-border text-text-color hover:bg-page-bg transition">Cancel</button>
            <button @click="executeDelete()" type="button" :disabled="isLoading" class="px-6 py-2.5 rounded-xl bg-red-600 text-white hover:bg-red-700 transition flex items-center justify-center gap-2 disabled:opacity-70">
                <i x-show="isLoading" class="ri-loader-4-line animate-spin"></i><span x-text="isLoading ? 'Deleting...' : 'Delete'"></span>
            </button>
        </div>
    </div>
</div>