<header 
    x-data="{ 
        darkMode: localStorage.getItem('theme') === 'dark',
        toggleTheme() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    }" 
    x-init="$watch('darkMode', val => val ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')); if(darkMode) document.documentElement.classList.add('dark');"
    class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 h-16 flex items-center justify-between px-6 shadow-sm z-10 sticky top-0 transition-colors duration-300"
>
    
    <button id="sidebarToggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 focus:outline-none transition-colors">
        <i class="ri-menu-2-line text-xl"></i>
    </button>

    <div class="flex items-center gap-4">
        
        <button @click="toggleTheme()" class="p-2 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <i x-show="darkMode" class="ri-sun-line text-xl text-yellow-500"></i>
            <i x-show="!darkMode" class="ri-moon-line text-xl"></i>
        </button>

        <button class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 relative">
            <i class="ri-notification-3-line text-xl"></i>
            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border border-white dark:border-gray-900"></span>
        </button>

        <div class="h-8 w-8 rounded-full bg-gradient-to-tr from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold text-sm shadow-md cursor-pointer">
            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
        </div>
    </div>
</header>