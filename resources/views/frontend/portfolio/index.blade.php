<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $about->name ?? 'Portfolio' }} | {{ __('messages.software_engineer') }}</title>

    @if(isset($shop) && $shop->fav)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $shop->fav) }}">
    @elseif(isset($about) && $about->image)
        {{-- បើអត់មាន Logo ក្រុមហ៊ុនទេ វានឹងយករូប Profile របស់អ្នកធ្វើជា Logo Tab ជំនួស --}}
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $about->image) }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <style>
        body { transition: background-color 0.5s ease, color 0.5s ease; }
        .glass-2026 {
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
        }
        .dark .glass-2026 {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        .dark .glass-nav {
            background: rgba(15, 23, 42, 0.8);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .dark ::-webkit-scrollbar-thumb { background: #475569; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    {{-- Script សម្រាប់ឆែក Dark Mode ភ្លាមៗមុនពេលទំព័រដើរ ដើម្បីកុំឱ្យលោតពន្លឺស (Flash White) --}}
    <script>
        (function() {
            const isDark = localStorage.getItem('theme_mode') === 'dark' || 
                          (!('theme_mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
</head>

{{-- Alpine Component គ្រប់គ្រងលើ Body ផ្ទាល់ --}}
<body class="bg-slate-50 text-slate-900 dark:bg-[#0B1120] dark:text-white overflow-x-hidden relative selection:bg-accent-500 selection:text-white"
      x-data="{ 
          darkMode: localStorage.getItem('theme_mode') === 'dark' || (!('theme_mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
          toggleTheme() {
              this.darkMode = !this.darkMode;
              localStorage.setItem('theme_mode', this.darkMode ? 'dark' : 'light');
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
              } else {
                  document.documentElement.classList.remove('dark');
              }
          }
      }">

    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10 transition-opacity duration-700">
        <div class="absolute top-[-10%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-blue-200/50 dark:bg-blue-900/20 blur-[120px] mix-blend-multiply dark:mix-blend-screen animate-blob"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50vw] h-[50vw] rounded-full bg-teal-200/50 dark:bg-indigo-900/20 blur-[120px] mix-blend-multiply dark:mix-blend-screen animate-blob" style="animation-delay: -5s;"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMTI4LCAxMjgsIDEyOCwgMC4xNSkiLz48L3N2Zz4=')] [mask-image:linear-gradient(to_bottom,white,transparent)] dark:opacity-30 opacity-60"></div>
    </div>

    {{-- Navigation Bar --}}
    <div x-data="{ scrolled: false, languageOpen: false }" @scroll.window="scrolled = (window.pageYOffset > 20)" 
         :class="{ 'glass-nav shadow-sm': scrolled, 'bg-transparent': !scrolled }"
         class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        
        <nav class="px-4 py-4 md:py-5 max-w-7xl mx-auto flex items-center justify-between">
            
            <a href="#home" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 flex items-center justify-center font-black text-lg transition-transform group-hover:scale-105">
                    {{ substr($about->name ?? 'P', 0, 1) }}
                </div>
                <span class="font-bold text-lg tracking-tight hidden sm:block">{{ $about->name ?? 'Portfolio' }}</span>
            </a>

            <div class="hidden md:flex items-center space-x-1 glass-2026 px-2 py-1 rounded-full">
                <a href="#projects" class="px-5 py-2 rounded-full text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-white/50 dark:hover:bg-slate-800 transition-all">{{ __('messages.projects') ?? 'Projects' }}</a>
                <a href="#experience" class="px-5 py-2 rounded-full text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-white/50 dark:hover:bg-slate-800 transition-all">{{ __('messages.experience') ?? 'Experience' }}</a>
                <a href="#tools" class="px-5 py-2 rounded-full text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-white/50 dark:hover:bg-slate-800 transition-all">{{ __('messages.stack') ?? 'Stack' }}</a>
            </div>
            
            <div class="flex items-center gap-3">
                
                {{-- Language Switcher (យកលំនាំតាម Admin Dashboard) --}}
                <div class="relative">
                    <button @click="languageOpen = !languageOpen" class="flex items-center gap-2 p-2.5 rounded-full hover:bg-slate-200 dark:hover:bg-slate-800 transition-colors text-slate-700 dark:text-slate-300">
                        @if(App::getLocale() == 'km')
                            <img src="https://flagcdn.com/w40/kh.png" alt="Khmer" class="w-5 h-auto rounded-sm shadow-sm object-cover">
                            <span class="text-xs font-bold uppercase tracking-wider hidden sm:block">KH</span>
                        @else
                            <img src="https://flagcdn.com/w40/us.png" alt="English" class="w-5 h-auto rounded-sm shadow-sm object-cover">
                            <span class="text-xs font-bold uppercase tracking-wider hidden sm:block">EN</span>
                        @endif
                    </button>

                    <div x-show="languageOpen" 
                         @click.outside="languageOpen = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-40 glass-2026 rounded-lg shadow-lg py-1 border border-slate-200 dark:border-slate-700 z-50 origin-top-right"
                         style="display: none;">
                        
                        <a href="{{ route('switch.language', 'km') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-white/50 dark:hover:bg-slate-800 transition-colors {{ App::getLocale() == 'km' ? 'bg-white/60 dark:bg-slate-800 font-semibold' : '' }}">
                            <img src="https://flagcdn.com/w40/kh.png" alt="Khmer" class="w-5 h-auto rounded-sm shadow-sm">
                            <span>ភាសាខ្មែរ</span>
                            @if(App::getLocale() == 'km') <i class="ri-check-line ml-auto text-accent-500"></i> @endif
                        </a>

                        <a href="{{ route('switch.language', 'en') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-white/50 dark:hover:bg-slate-800 transition-colors {{ App::getLocale() == 'en' ? 'bg-white/60 dark:bg-slate-800 font-semibold' : '' }}">
                            <img src="https://flagcdn.com/w40/us.png" alt="English" class="w-5 h-auto rounded-sm shadow-sm">
                            <span>English</span>
                            @if(App::getLocale() == 'en') <i class="ri-check-line ml-auto text-accent-500"></i> @endif
                        </a>
                    </div>
                </div>

                {{-- Dark Mode Toggle --}}
                <button type="button" @click="toggleTheme()" class="p-2.5 rounded-full hover:bg-slate-200 dark:hover:bg-slate-800 transition-colors text-slate-700 dark:text-slate-300">
                    <i class="ri-moon-fill text-lg" x-show="!darkMode"></i>
                    <i class="ri-sun-fill text-lg" x-show="darkMode" style="display: none;"></i>
                </button>

                <a href="#contact" class="hidden sm:inline-block bg-accent-600 text-white px-6 py-2.5 rounded-full text-sm font-bold hover:bg-accent-500 hover:shadow-lg hover:shadow-accent-500/30 transition-all">
                    {{ __('messages.lets_talk') ?? "Let's Talk" }}
                </a>
            </div>
        </nav>
    </div>

    <section id="home" class="relative pt-40 pb-20 md:pt-48 md:pb-24 px-4 max-w-7xl mx-auto min-h-[90vh] flex flex-col justify-center">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7 order-2 lg:order-1 text-center lg:text-left" data-aos="fade-right" data-aos-duration="1000">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-2026 mb-6">
                    <span class="w-2 h-2 rounded-full bg-accent-500 animate-pulse"></span>
                    <span class="text-xs md:text-sm font-bold uppercase tracking-widest text-slate-600 dark:text-slate-300">
                        {{ App::getLocale() == 'en' ? 'Available for work' : 'ស្វាគមន៍សម្រាប់ការងារ' }}
                    </span>
                </div>
                
                <h1 class="text-5xl md:text-7xl lg:text-[5rem] font-black leading-[1.05] tracking-tight mb-6 text-slate-900 dark:text-white">
                    <span>{{ App::getLocale() == 'en' ? 'Crafting digital' : 'បង្កើតបទពិសោធន៍' }}</span>
                    <br />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent-500 to-indigo-500">
                        {{ App::getLocale() == 'en' ? 'experiences.' : 'ឌីជីថលពិតៗ.' }}
                    </span>
                </h1>
                
                <p class="text-lg md:text-2xl text-slate-600 dark:text-slate-400 mb-10 max-w-2xl mx-auto lg:mx-0 font-medium leading-relaxed">
                    {{ $about->description ?? "I'm a software developer specialized in building modern, scalable, and user-centric applications." }}
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="#projects" class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-8 py-4 rounded-full text-base font-bold hover:scale-105 transition-transform flex items-center justify-center gap-2 shadow-xl">
                        <span>{{ App::getLocale() == 'en' ? 'View Selected Work' : 'មើលស្នាដៃ' }}</span> <i class="ri-arrow-right-line"></i>
                    </a>
                    <a href="{{ asset('path/to/your/cv.pdf') }}" target="_blank" class="glass-2026 px-8 py-4 rounded-full text-base font-bold hover:bg-slate-100 dark:hover:bg-slate-800 transition-all flex items-center justify-center gap-2 border border-slate-300 dark:border-slate-700">
                        <i class="ri-file-download-line text-xl"></i> <span>{{ App::getLocale() == 'en' ? 'Download CV' : 'ទាញយក CV' }}</span>
                    </a>
                </div>
            </div>

            <div class="lg:col-span-5 order-1 lg:order-2 flex justify-center lg:justify-end" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                <div class="relative w-64 h-64 md:w-80 md:h-80 glass-2026 rounded-[3rem] p-3 transform rotate-3 hover:rotate-0 transition-all duration-500 shadow-2xl">
                    <div class="absolute inset-0 bg-gradient-to-tr from-accent-500/20 to-purple-500/20 rounded-[3rem] blur-xl -z-10"></div>
                    @if($about->image)
                        <img src="{{ asset('storage/' . $about->image) }}" alt="Profile" class="w-full h-full rounded-[2.5rem] object-cover shadow-inner">
                    @else
                        <div class="w-full h-full rounded-[2.5rem] bg-slate-200 dark:bg-slate-800 flex items-center justify-center text-6xl text-slate-400">
                            <i class="ri-user-smile-fill"></i>
                        </div>
                    @endif
                    <div class="absolute -bottom-6 -left-6 glass-2026 p-4 rounded-2xl flex items-center gap-3 animate-[bounce_4s_infinite] shadow-lg">
                        <div class="w-12 h-12 rounded-full bg-accent-500/20 flex items-center justify-center text-accent-500 text-2xl">
                            <i class="ri-code-s-slash-line"></i>
                        </div>
                        <div>
                            <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 font-bold uppercase">{{ App::getLocale() == 'en' ? 'Developer' : 'អ្នកសរសេរកូដ' }}</p>
                            <p class="text-sm md:text-base font-black text-slate-900 dark:text-white">{{ $about->name ?? 'Pro' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="w-full py-12 border-y border-slate-200/60 dark:border-white/10 glass-2026 relative overflow-hidden flex z-10 my-10 shadow-sm">
        <div class="absolute inset-y-0 left-0 w-24 md:w-40 bg-gradient-to-r from-slate-50 dark:from-[#0B1120] to-transparent z-10 pointer-events-none"></div>
        <div class="absolute inset-y-0 right-0 w-24 md:w-40 bg-gradient-to-l from-slate-50 dark:from-[#0B1120] to-transparent z-10 pointer-events-none"></div>

        <div class="flex w-max animate-marquee hover:[animation-play-state:paused]">
            @for ($i = 0; $i < 3; $i++)
                <div class="flex shrink-0 items-center justify-around gap-6 md:gap-10 px-4 md:px-5">
                    @foreach($technologies as $tech)
                        @if($tech->image)
                            <div class="flex flex-col items-center justify-center group glass-2026 px-6 py-5 rounded-3xl min-w-[130px] md:min-w-[160px] hover:-translate-y-2 transition-transform duration-300 shadow-sm hover:shadow-lg border border-white/80 dark:border-white/10 bg-white/40 dark:bg-slate-800/40">
                                <img src="{{ asset('storage/' . $tech->image) }}" alt="{{ $tech->name }}" class="h-14 md:h-16 w-auto object-contain transition-transform duration-300 mb-3 drop-shadow-md group-hover:scale-110">
                                <span class="text-sm md:text-base font-extrabold text-slate-700 dark:text-slate-200">{{ $tech->name }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endfor
        </div>
    </div>

    <section id="tools" class="py-24 px-4 relative z-10">
        <div class="max-w-5xl mx-auto text-center">
            <div data-aos="fade-up" class="mb-14">
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white mb-4">{{ App::getLocale() == 'en' ? 'The Stack' : 'ឧបករណ៍បច្ចេកវិទ្យា' }}</h2>
                <p class="text-lg md:text-xl text-slate-600 dark:text-slate-400 font-medium">{{ App::getLocale() == 'en' ? 'Tools and platforms I use to build robust applications.' : 'កម្មវិធី និងឧបករណ៍ដែលខ្ញុំប្រើប្រាស់ជាប្រចាំ។' }}</p>
            </div>
            
            <div class="flex flex-wrap justify-center gap-4 md:gap-6">
                @forelse($tools as $index => $tool)
                    <div class="flex flex-col items-center justify-center w-28 h-28 md:w-36 md:h-36 glass-2026 rounded-3xl hover:-translate-y-2 transition-all duration-300 shadow-sm hover:shadow-xl border border-white/80 dark:border-white/10 bg-white/40 dark:bg-slate-800/40"
                         data-aos="zoom-in" data-aos-delay="{{ $index * 50 }}">
                        @if($tool->image)
                            <img src="{{ asset('storage/' . $tool->image) }}" alt="{{ $tool->name }}" class="h-10 md:h-14 w-auto object-contain mb-3 drop-shadow-sm hover:scale-110 transition-transform duration-300">
                        @endif
                        <span class="text-xs md:text-sm font-bold text-slate-800 dark:text-slate-200">{{ $tool->name }}</span>
                    </div>
                @empty
                    <p class="text-base text-slate-500 dark:text-slate-400 w-full text-center">No tools available at the moment.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section id="projects" class="py-24 px-4 relative z-10">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6" data-aos="fade-up">
                <div>
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 dark:text-white tracking-tight mb-4">{{ App::getLocale() == 'en' ? 'Selected Projects' : 'ស្នាដៃលេចធ្លោ' }}</h2>
                    <p class="text-lg md:text-xl text-slate-600 dark:text-slate-400 font-medium max-w-2xl">{{ App::getLocale() == 'en' ? 'A showcase of my recent work and technical problem-solving.' : 'ការបង្ហាញពីស្នាដៃថ្មីៗ និងការដោះស្រាយបញ្ហាបច្ចេកទេស។' }}</p>
                </div>
                
                <a href="https://github.com/Tangkoan" target="_blank" class="group flex items-center gap-2 glass-2026 px-6 py-3 rounded-full text-base font-bold text-slate-700 dark:text-slate-200 hover:bg-white dark:hover:bg-slate-800 transition-all shadow-sm hover:shadow-md border border-slate-200 dark:border-slate-700">
                    GitHub <i class="ri-github-fill text-2xl group-hover:rotate-12 transition-transform"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10">
                @forelse($projects as $index => $project)
                    <div class="group glass-2026 rounded-[2.5rem] p-3 hover:shadow-2xl transition-all duration-500 flex flex-col h-full border border-white/80 dark:border-white/10" 
                         data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="relative w-full aspect-[4/3] rounded-[2rem] overflow-hidden mb-5 bg-slate-100 dark:bg-slate-800">
                            @if($project->image)
                                <img src="{{ asset('storage/' . $project->image) }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-in-out">
                            @endif
                            <div class="absolute inset-0 bg-slate-900/10 group-hover:bg-transparent transition-colors duration-500"></div>
                            
                            @if($project->url_project)
                                <a href="{{ $project->url_project }}" target="_blank" class="absolute bottom-4 right-4 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md text-slate-900 dark:text-white h-14 w-14 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-300 hover:scale-110 shadow-xl">
                                    <i class="ri-arrow-right-up-line text-2xl"></i>
                                </a>
                            @endif
                        </div>
                        <div class="px-5 pb-5 flex-1 flex flex-col">
                            <span class="text-xs md:text-sm font-black text-accent-500 uppercase tracking-widest mb-2">{{ $project->sup_name }}</span>
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2 leading-tight">{{ $project->name }}</h3>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-24 glass-2026 rounded-[2rem] flex flex-col items-center justify-center text-slate-500 dark:text-slate-400">
                        <i class="ri-code-box-line text-5xl mb-4 opacity-50"></i>
                        <p class="font-medium text-lg">{{ App::getLocale() == 'en' ? 'Cooking new projects...' : 'កំពុងរៀបចំគម្រោងថ្មី...' }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="experience" class="py-24 px-4 relative z-10">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-20" data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 dark:text-white mb-4">{{ App::getLocale() == 'en' ? 'Professional Journey' : 'បទពិសោធន៍ការងារ' }}</h2>
                <p class="text-lg md:text-xl text-slate-600 dark:text-slate-400 font-medium">{{ App::getLocale() == 'en' ? 'My timeline of growth and value delivery.' : 'ពេលវេលានៃការរីកចម្រើន និងការបង្កើតតម្លៃ។' }}</p>
            </div>

            <div class="relative space-y-8 md:space-y-12 before:absolute before:inset-0 before:ml-6 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-1 before:bg-gradient-to-b before:from-transparent before:via-slate-300 dark:before:via-slate-700 before:to-transparent">
                @foreach($experiences as $index => $exp)
                    @php 
                        $startYear = \Carbon\Carbon::parse($exp->start_day)->format('M Y');
                        $endYear = $exp->end_day ? \Carbon\Carbon::parse($exp->end_day)->format('M Y') : (App::getLocale() == 'en' ? 'Present' : 'បច្ចុប្បន្ន');
                    @endphp
                    
                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full border-4 border-slate-50 dark:border-[#0B1120] bg-slate-200 dark:bg-slate-700 group-hover:bg-accent-500 text-slate-500 dark:text-slate-400 group-hover:text-white shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow-sm transition-colors duration-300 relative z-20">
                            <i class="ri-briefcase-line text-xl"></i>
                        </div>
                        
                        <div class="w-[calc(100%-4rem)] md:w-[calc(50%-3rem)] glass-2026 p-6 md:p-8 rounded-3xl hover:border-accent-500/50 transition-colors duration-300 shadow-sm border border-white/50 dark:border-white/5">
                            <div class="flex flex-col gap-1 mb-3">
                                <span class="text-xs md:text-sm font-black text-accent-500 uppercase tracking-widest">{{ $startYear }} — {{ $endYear }}</span>
                                <h3 class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white">{{ $exp->name }}</h3>
                            </div>
                            <p class="text-base font-semibold text-slate-600 dark:text-slate-400 flex items-center gap-2">
                                <i class="ri-building-line"></i> {{ $exp->sup_name }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section x-data="{ isModalOpen: false, modalImage: '' }" class="py-24 px-4 relative z-10 border-t border-slate-200/50 dark:border-white/10">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 dark:text-white mb-4">{{ App::getLocale() == 'en' ? 'Certifications' : 'វិញ្ញាបនបត្រ' }}</h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
                @forelse($certificates as $index => $cert)
                    <div @click="isModalOpen = true; modalImage = '{{ asset('storage/' . $cert->image) }}'" 
                         class="group relative rounded-3xl overflow-hidden glass-2026 aspect-[4/3] cursor-zoom-in hover:shadow-2xl transition-all duration-300 border border-white/50 dark:border-white/10"
                         data-aos="zoom-in" data-aos-delay="{{ $index * 50 }}">
                        @if($cert->image)
                            <img src="{{ asset('storage/' . $cert->image) }}" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                        @endif
                        <div class="absolute inset-0 bg-slate-900/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <i class="ri-zoom-in-line text-4xl text-white"></i>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-center text-lg text-slate-500 dark:text-slate-400">No certificates to show.</p>
                @endforelse
            </div>
        </div>

        <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/90 backdrop-blur-md"
             x-transition.opacity.duration.300ms>
            <button @click="isModalOpen = false" class="absolute top-6 right-6 text-white/50 hover:text-white transition-colors z-50 p-2 glass-2026 rounded-full border border-white/20">
                <i class="ri-close-line text-3xl"></i>
            </button>
            <img :src="modalImage" @click.outside="isModalOpen = false" class="max-w-full max-h-[90vh] object-contain rounded-2xl shadow-2xl border border-white/10"
                 x-show="isModalOpen" x-transition.scale.80.duration.300ms>
        </div>
    </section>

    <footer id="contact" class="py-24 px-4 relative z-10 glass-2026 mt-10">
        <div class="max-w-4xl mx-auto text-center" data-aos="fade-up">
            <h2 class="text-4xl md:text-6xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">{{ App::getLocale() == 'en' ? 'Let\'s collaborate.' : 'តោះសហការគ្នា.' }}</h2>
            <p class="text-slate-600 dark:text-slate-400 mb-12 max-w-lg mx-auto font-medium text-lg md:text-xl">{{ App::getLocale() == 'en' ? 'Got a project? Drop me a line if you want to work together.' : 'មានគម្រោងមែនទេ? ទាក់ទងមកខ្ញុំបើអ្នកចង់ធ្វើការជាមួយគ្នា។' }}</p>
            
            <a href="mailto:kuytangkoan@gmail.com" class="inline-flex items-center gap-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-10 py-5 rounded-full font-bold text-lg hover:scale-105 transition-transform shadow-xl mb-16 border border-slate-700 dark:border-slate-300">
                kuytangkoan@gmail.com <i class="ri-send-plane-fill text-xl"></i>
            </a>

            <div class="flex justify-center flex-wrap gap-4 mb-16">
                @foreach($socials as $social)
                    <a href="{{ $social->url_social }}" target="_blank" title="{{ $social->name }}" class="h-16 w-16 rounded-full glass-2026 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-900 hover:-translate-y-2 transition-all duration-300 shadow-sm overflow-hidden group border border-slate-300 dark:border-slate-700">
                        @if($social->image)
                            <img src="{{ asset('storage/' . $social->image) }}" alt="{{ $social->name }}" class="w-8 h-8 object-contain transition-transform duration-300 group-hover:scale-110">
                        @else
                            <span class="text-xl font-black">{{ substr($social->name, 0, 1) }}</span>
                        @endif
                    </a>
                @endforeach
            </div>

            <div class="pt-10 border-t border-slate-300/30 dark:border-white/10 flex flex-col items-center gap-8">
                <div class="text-slate-500 dark:text-slate-400 text-sm font-bold uppercase tracking-widest flex flex-col md:flex-row justify-between items-center gap-4 w-full">
                    <p>© {{ date('Y') }} <span>{{ App::getLocale() == 'en' ? 'Kuy Tangkoan' : 'គុយ តាំងគន់' }}</span></p>
                    <p><span>{{ App::getLocale() == 'en' ? 'Designed with' : 'រចនាដោយដាក់' }}</span><i class="ri-heart-fill text-red-500 animate-pulse text-base mx-1"></i> <span>{{ App::getLocale() == 'en' ? 'IN' : 'នៅ' }}</span> <span>{{ App::getLocale() == 'en' ? 'Siem Reap' : 'សៀមរាប' }}</span></p>
                </div>
                
                <div class="inline-flex items-center gap-3 px-8 py-4 rounded-full glass-2026 border border-slate-300 dark:border-slate-700 shadow-md hover:scale-105 transition-transform">
                    <span class="text-sm font-semibold text-slate-600 dark:text-slate-300 tracking-wide">{{ App::getLocale() == 'en' ? 'Proudly built with' : 'បង្កើតឡើងដោយប្រើ' }}</span>
                    <div class="flex items-center gap-3">
                        <span class="text-sm md:text-base font-black text-[#FF2D20]">Laravel Blade</span>
                        <span class="text-slate-400 dark:text-slate-500 text-sm">+</span>
                        <span class="text-sm md:text-base font-black text-[#38B2AC]">Tailwind CSS</span>
                        <span class="text-slate-400 dark:text-slate-500 text-sm">+</span>
                        <span class="text-sm md:text-base font-black text-[#00758F]">MySQL</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <button x-data="{ show: false }" 
            @scroll.window="show = window.pageYOffset > 500" 
            x-show="show" 
            x-transition.opacity.translate.bottom.duration.300ms
            @click="window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="fixed bottom-8 right-8 z-50 p-4 md:p-5 bg-accent-600 text-white rounded-full shadow-2xl hover:bg-accent-500 transition-colors focus:outline-none flex items-center justify-center group"
            style="display: none;">
        <i class="ri-arrow-up-line text-2xl group-hover:-translate-y-1 transition-transform"></i>
    </button>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                once: true,
                offset: 50,
                duration: 800,
                easing: 'ease-out-cubic',
            });
        });

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>