<header 
    x-data="{ userDropdownOpen: false }" 
    x-effect="if ($store.theme.darkMode) document.documentElement.classList.add('dark'); else document.documentElement.classList.remove('dark');"
    class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 h-16 flex items-center justify-between px-6 shadow-sm z-10 sticky top-0 transition-colors duration-300">

    <button id="sidebarToggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 transition-colors">
        <i class="ri-menu-2-line text-xl"></i>
    </button>

    <div class="flex items-center gap-4">
        
        <button type="button" @click="$store.theme.setMode($store.theme.darkMode ? 'light' : 'dark')" class="relative inline-flex h-8 w-14 items-center rounded-full transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900" :class="!$store.theme.darkMode ? 'bg-gray-200' : ''" :style="$store.theme.darkMode ? 'background-color: var(--primary, #308D71)' : ''">
            <span class="sr-only">Toggle Dark Mode</span>
            <span class="inline-block h-6 w-6 transform rounded-full bg-white shadow-md transition duration-300 ease-in-out flex items-center justify-center" :class="$store.theme.darkMode ? 'translate-x-7' : 'translate-x-1'">
                <i x-show="!$store.theme.darkMode" class="ri-sun-fill text-yellow-500 text-sm"></i>
                <i x-show="$store.theme.darkMode" class="ri-moon-fill text-sm" :style="'color: var(--primary, #308D71)'"></i>
            </span>
        </button>

        <button class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 relative">
            <i class="ri-notification-3-line text-xl"></i>
            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border border-white dark:border-gray-900"></span>
        </button>

        <div class="relative ">
           <button @click="userDropdownOpen = !userDropdownOpen" 
                    class="h-8 w-8 rounded-full flex items-center  justify-center text-primary font-bold text-sm shadow-md cursor-pointer focus:outline-none ring-2 ring-secondary focus:ring-primary transition-all p-0 overflow-hidden
                    {{ Auth::user()->avatar ? 'bg-transparent' : 'bg-gradient-to-tr' }}">
                
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                        alt="{{ Auth::user()->name }}" 
                        class="h-full w-full object-cover">
                @else
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                @endif

            </button>

            <div x-show="userDropdownOpen" 
                 @click.outside="userDropdownOpen = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-1 border border-gray-100 dark:border-gray-700 z-50 origin-top-right"
                 style="display: none;">
                
                <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                </div>

                <a href="{{ route('admin.profile') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="ri-user-line mr-2"></i> User Info
                </a>

                <a href="{{ route('admin.password') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="ri-lock-password-line mr-2"></i> Change Password
                </a>

                {{-- <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                        <i class="ri-logout-box-line mr-2"></i> Logout
                    </button>
                </form> --}}
                <button 
                    {{-- ហៅ Function ខាងក្រោមពេលចុច --}}
                    @click.prevent="logoutUser()" 
                    type="button" 
                    class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                    
                    <i class="ri-logout-box-line mr-2"></i> Logout
                </button>
                {{-- Script សម្រាប់គ្រប់គ្រងការ Logout --}}
                <script>
                    function logoutUser() {
                        // ១. ហៅទៅកាន់ AuthController តាមរយៈ Fetch API
                        fetch("{{ route('logout') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            // ២. ពេល Logout ជោគជ័យ និងទទួលបាន redirect_url
                            if (data.redirect_url) {
                                // ប្រើ Livewire.navigate ដើម្បីប្តូរ Page ដោយមិន Refresh
                                Livewire.navigate(data.redirect_url);
                            } else {
                                // ករណីបន្ទាន់ (Fallback)
                                window.location.href = '/login';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // បើមានបញ្ហា អោយវា Refresh ធម្មតាទៅ
                            window.location.reload(); 
                        });
                    }
                </script>
            </div>
        </div>

    </div>
</header>