<div class="px-6 py-4 border-t border-border-color flex flex-col sm:flex-row justify-between items-center gap-4" 
     x-show="pagination.total > 0" 
     style="display: none;"> {{-- style display none ដើម្បីកុំអោយឃើញភ្លឹបភ្លែតពេល load --}}
    
    {{-- 1. Show Per Page Dropdown --}}
    <div class="flex items-center gap-2">
        <span class="text-sm text-secondary whitespace-nowrap">Show:</span>
        {{-- x-model="perPage" នឹងតភ្ជាប់ទៅ parent scope ដោយស្វ័យប្រវត្តិ --}}
        <select x-model="perPage" 
                @change="gotoPage(1)" 
                class="w-24 bg-page-bg border border-input-border text-text-color text-sm rounded-lg focus:ring-primary focus:border-primary block p-2 outline-none cursor-pointer">
            <option value="1">1</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="all">All</option>
        </select>
    </div>

    {{-- 2. Pagination Buttons --}}
    <div class="flex items-center gap-1">
        {{-- Previous Button --}}
        <button 
            @click="gotoPage(currentPage - 1)" 
            :disabled="currentPage === 1"
            class="px-3 py-1 text-sm border rounded hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed text-text-color border-input-border transition-colors">
            &laquo;
        </button>

        {{-- Page Numbers Loop --}}
        <template x-for="page in pagination.last_page" :key="page">
            <button 
                x-show="page === 1 || page === pagination.last_page || (page >= currentPage - 2 && page <= currentPage + 2)"
                @click="gotoPage(page)" 
                x-text="page"
                :class="currentPage === page ? 'bg-primary text-white border-primary' : 'text-text-color hover:bg-gray-100 border-input-border'"
                class="px-3 py-1 text-sm border rounded transition-colors duration-200">
            </button>
        </template>

        {{-- Next Button --}}
        <button 
            @click="gotoPage(currentPage + 1)" 
            :disabled="currentPage === pagination.last_page"
            class="px-3 py-1 text-sm border rounded hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed text-text-color border-input-border transition-colors">
            &raquo;
        </button>
    </div>
</div>