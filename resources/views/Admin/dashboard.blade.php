<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ice Cream Admin</title>
    
   @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <style>
        /* --- Sidebar Transition --- */
        /* 1. ពេលធំ (Normal State): អោយមាន Animation រលូន */
        #sidebar { 
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        }

        /* 2. ពេលតូច (Collapsed State): បិទ Animation ចោលភ្លាម!
            - ផលប្រយោជន៍ទី ១: ពេលចុចបង្រួម វាលោតទៅតូចភ្លាម (Snap) លឿនទាន់ចិត្ត
            - ផលប្រយោជន៍ទី ២: ពេល Refresh Page (បើចាំថាជាតូច) វានឹងមិនឃើញ "Effect រួមតូច" ទេ គឺតូចស្រាប់តែម្តង
        */
        body.collapsed #sidebar {
            transition: none !important;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* --- Collapsed State (ពេលតូច) --- */
        body.collapsed .sidebar-text, 
        body.collapsed .arrow-icon,
        body.collapsed .tree-line { display: none !important; }

        body.collapsed #sidebar .menu-item-content { justify-content: center; padding-left: 0; padding-right: 0; }
        body.collapsed #sidebar .menu-icon { margin-right: 0; }

        /* Hover Popup ពេលតូច */
        body.collapsed .group:hover .submenu {
            display: block !important;
            position: absolute;
            left: 100%; top: 0; margin-left: 10px; width: 220px;
            background-color: #0f172a; border: 1px solid #1e293b;
            border-radius: 12px; padding: 10px; z-index: 50;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
        }
        body.collapsed .group:hover .tooltip { display: block !important; }

        /* --- Expanded State (ពេលធំ) --- */
        body:not(.collapsed) .submenu { position: relative; }

        /* 1. ពេល Sidebar តូច ត្រូវដក Scroll ចេញ ហើយអោយ Content លៀនចេញក្រៅបាន */
        body.collapsed #sidebar,
        body.collapsed #sidebar nav {
            overflow: visible !important; 
        }

        /* 2. ធានាថា Submenu ស្ថិតនៅលើគេបង្អស់ (Highest Z-Index) */
        body.collapsed .group:hover .submenu {
            z-index: 9999 !important; /* លេខធំបំផុត */
        }
        
        /* 3. (Optional) បើសិនជាមានបញ្ហាជាមួយ Header, បន្ថយ Z-Index Header បន្តិច */
        header {
            z-index: 40; /* តូចជាង Sidebar (z-50) */
        }


        /* 1. កំណត់ទម្រង់ដើមរបស់ Submenu ពេល Sidebar តូច */
    /* 1. ស្ថានភាពធម្មតា (ពេលកំពុងលាក់) */
    body.collapsed .submenu {
        /* Positioning (ដូចមុន) */
        display: block !important;
        position: absolute;
        left: 100%;
        top: 0;
        margin-left: 0.5rem;
        width: 14rem;
        background-color: #0f172a;
        border: 1px solid #1e293b;
        border-radius: 0.75rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
        z-index: 9999 !important;

        /* --- កែត្រង់នេះ (NO ANIMATION) --- */
        opacity: 0;
        visibility: hidden;
        
        /* Trick: យើងកំណត់ duration ជា 0s (អោយបាត់ភ្លាម)
           ប៉ុន្តែដាក់ delay 0.3s (អោយរង់ចាំ 0.3s សិនចាំបាត់)
        */
        transition: opacity 0s linear 0.3s, visibility 0s linear 0.3s;
    }

    /* 2. ពេលដាក់ Mouse ចូល (Show Immediately) */
    body.collapsed .group:hover .submenu,
    body.collapsed .submenu:hover { 
        /* បង្ហាញភ្លាមៗ */
        opacity: 1;
        visibility: visible;
        
        /* ពេលបង្ហាញ គឺអោយចេញមកភ្លាមៗ មិនបាច់ចាំ (Delay 0s) */
        transition-delay: 0s; 
    }

    /* Bridge (ស្ពាន) */
    body.collapsed .submenu::before {
        content: '';
        position: absolute;
        top: 0; bottom: 0; left: -1rem; width: 1rem;
        background: transparent;
    }
    </style>
</head>
<body class="bg-slate-50 font-sans flex h-screen overflow-hidden">

    @include('partials.sidebar')

    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        
        @include('partials.header')

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-6">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebarToggle');
            
            // 1. Local Storage Check
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            if (isCollapsed) {
                body.classList.add('collapsed');
                sidebar.classList.remove('w-72');
                sidebar.classList.add('w-20');
                document.querySelectorAll('.arrow-icon').forEach(el => el.classList.remove('rotate-180'));
            }

            // 2. Toggle Click
            if(toggleBtn){
                toggleBtn.addEventListener('click', () => {
                    body.classList.toggle('collapsed');
                    const isNowCollapsed = body.classList.contains('collapsed');
                    localStorage.setItem('sidebar-collapsed', isNowCollapsed);

                    if (isNowCollapsed) {
                        sidebar.classList.remove('w-72');
                        sidebar.classList.add('w-20');
                        document.querySelectorAll('.arrow-icon').forEach(el => el.classList.remove('rotate-180'));
                    } else {
                        sidebar.classList.remove('w-20');
                        sidebar.classList.add('w-72');
                    }
                });
            }
        });

        // 3. Dropdown Function
        function toggleSubmenu(button) {
            if (document.body.classList.contains('collapsed')) return;
            const submenu = button.nextElementSibling;
            const arrow = button.querySelector('.arrow-icon');
            submenu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }
    </script>
</body>
</html>