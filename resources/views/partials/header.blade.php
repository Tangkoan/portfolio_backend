<header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6 shadow-sm z-10 sticky top-0">
    
    <button id="sidebarToggle" class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 focus:outline-none transition-colors">
        <i class="ri-menu-2-line text-xl"></i>
    </button>

    <div class="flex items-center gap-4">
        <button class="p-2 text-gray-400 hover:text-gray-600 relative">
            <i class="ri-notification-3-line text-xl"></i>
            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
        </button>
        <div class="h-8 w-8 rounded-full bg-gradient-to-tr from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold text-sm shadow-md">
            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
        </div>
    </div>
</header>