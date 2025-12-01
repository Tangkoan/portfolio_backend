<div x-data="{ 
        notifications: [],
        add(type, message) {
            const id = Date.now();
            // បង្កើត notification តែយើងមិនទាន់ដាក់ Timeout ភ្លាមៗទេ នៅទីនេះ
            const notification = { id, type, message, timeout: null };
            this.notifications.push(notification);
            
            // ហៅ function resume ដើម្បីចាប់ផ្តើមរាប់ម៉ោង
            this.resume(id);
        },
        remove(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        },
        // Function សម្រាប់ផ្អាកពេលដាក់ Mouse
        pause(id) {
            const notification = this.notifications.find(n => n.id === id);
            if (notification && notification.timeout) {
                clearTimeout(notification.timeout); // លុបចោលការរាប់ម៉ោង
                notification.timeout = null;
            }
        },
        // Function សម្រាប់ដំណើរការវិញពេលដក Mouse
        resume(id) {
            const notification = this.notifications.find(n => n.id === id);
            if (notification) {
                // ដាក់ម៉ោងថ្មី (3 វិនាទី) ដើម្បីបិទ
                notification.timeout = setTimeout(() => { this.remove(id) }, 3000);
            }
        },
        getIcon(type) {
            switch(type) {
                case 'success': return 'ri-check-line';
                case 'error': return 'ri-error-warning-line';
                case 'warning': return 'ri-alert-line';
                case 'info': return 'ri-information-line';
                default: return 'ri-notification-3-line';
            }
        },
        getStyles(type) {
            switch(type) {
                case 'success': return { iconBg: 'bg-green-100 dark:bg-green-900/30', iconColor: 'text-green-600 dark:text-green-400', bar: 'bg-green-500' };
                case 'error': return { iconBg: 'bg-red-100 dark:bg-red-900/30', iconColor: 'text-red-600 dark:text-red-400', bar: 'bg-red-500' };
                case 'warning': return { iconBg: 'bg-amber-100 dark:bg-amber-900/30', iconColor: 'text-amber-600 dark:text-amber-400', bar: 'bg-amber-500' };
                case 'info': return { iconBg: 'bg-blue-100 dark:bg-blue-900/30', iconColor: 'text-blue-600 dark:text-blue-400', bar: 'bg-blue-500' };
                default: return { iconBg: 'bg-gray-100 dark:bg-gray-700', iconColor: 'text-gray-600 dark:text-gray-400', bar: 'bg-gray-500' };
            }
        }
    }"
    @notify.window="add($event.detail.type, $event.detail.message)"
    x-init="
        @if(session('success')) add('success', '{{ session('success') }}'); @endif
        @if(session('error')) add('error', '{{ session('error') }}'); @endif
        @if(session('warning')) add('warning', '{{ session('warning') }}'); @endif
        @if(session('info')) add('info', '{{ session('info') }}'); @endif
        @if($errors->any()) 
             @foreach($errors->all() as $error)
                add('error', '{{ $error }}');
             @endforeach
        @endif
    "
    class="fixed top-6 right-6 z-[9999] flex flex-col gap-2 w-auto pointer-events-none"
>
    <style>
        @keyframes shrink-progress { from { width: 100%; } to { width: 0%; } }
        .animate-progress { animation: shrink-progress 3s linear forwards; }
        
        /* កូដនេះធ្វើអោយ Animation ឈប់ដើរ ពេលដាក់ Mouse លើ Class group */
        .group:hover .animate-progress {
            animation-play-state: paused;
        }
    </style>

    <template x-for="notification in notifications" :key="notification.id">
        <div 
            x-transition:enter="transition ease-[cubic-bezier(0.68,-0.55,0.265,1.55)] duration-500"
            x-transition:enter-start="opacity-0 translate-x-12 scale-90"
            x-transition:enter-end="opacity-100 translate-x-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"

            class="pointer-events-auto relative w-full max-w-xs overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-xl ring-1 ring-black/5 dark:ring-white/10 group cursor-pointer"
            
            @mouseenter="pause(notification.id)" 
            @mouseleave="resume(notification.id)" 
            @click="remove(notification.id)"
        >
            <div class="p-3 flex items-center gap-3">
                <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center"
                     :class="getStyles(notification.type).iconBg">
                    <i :class="getIcon(notification.type) + ' ' + getStyles(notification.type).iconColor + ' text-lg'"></i>
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 dark:text-gray-100 leading-snug" x-text="notification.message"></p>
                </div>

                <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>

            <div class="h-[2px] w-full bg-gray-100 dark:bg-gray-700/50 absolute bottom-0 left-0">
                <div class="h-full animate-progress" :class="getStyles(notification.type).bar"></div>
            </div>
        </div>
    </template>
</div>