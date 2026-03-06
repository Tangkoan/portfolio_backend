<div class="mt-4 bg-card-bg rounded-xl shadow-sm border border-border-color px-4 py-3 flex flex-col sm:flex-row justify-between items-center gap-4" 
     x-show="pagination.total > 0" x-cloak>
    
    {{-- Per Page Dropdown --}}
    <div class="flex items-center gap-2">
        <span class="text-sm text-secondary whitespace-nowrap">Show:</span>
        <select x-model="perPage" @change="gotoPage(1)" class="w-20 bg-page-bg border border-input-border text-text-color text-sm rounded-lg focus:ring-primary focus:border-primary block p-2 outline-none cursor-pointer">
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
        <span class="text-sm text-secondary whitespace-nowrap">of <span x-text="pagination.total" class="font-bold text-text-color"></span> entries</span>
    </div>

    {{-- Controls --}}
    <div class="flex items-center gap-1">
        
        {{-- Previous Button --}}
        <button @click="gotoPage(currentPage - 1)" :disabled="currentPage === 1" 
                class="h-8 w-8 flex items-center justify-center text-sm border rounded-lg hover:bg-page-bg disabled:opacity-50 disabled:cursor-not-allowed text-text-color border-input-border transition-colors">
            <i class="ri-arrow-left-s-line"></i>
        </button>
        
        {{-- Next Button --}}
        <button @click="gotoPage(currentPage + 1)" :disabled="currentPage >= pagination.last_page" 
                class="h-8 px-3 flex items-center justify-center text-sm border rounded-lg bg-primary text-white hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed border-primary transition-colors gap-1">
            <span>Next</span> <i class="ri-arrow-right-s-line"></i>
        </button>
        
    </div>
</div>