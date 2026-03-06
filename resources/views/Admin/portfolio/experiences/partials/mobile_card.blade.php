<template x-for="item in experiences" :key="'mobile-' + item.id">
    <div class="bg-card-bg p-4 rounded-2xl shadow-sm border border-border-color relative mb-3">
        <input type="checkbox" :value="item.id" x-model="selectedIds" class="absolute top-3 left-3 z-20 h-5 w-5">

        <div class="pl-8 flex flex-col gap-1">
            <h3 class="font-bold text-text-color text-lg" x-text="item.name"></h3>
            <p class="text-sm font-semibold text-primary" x-show="showCols.company">
                <i class="ri-building-line mr-1"></i><span x-text="item.sup_name"></span>
            </p>
            <p class="text-xs text-secondary mt-1" x-show="showCols.duration">
                <i class="ri-calendar-line mr-1"></i>
                <span x-text="item.start_day"></span> to <span x-text="item.end_day ? item.end_day : 'Present'"></span>
            </p>

            <div class="flex items-center justify-between mt-3 pt-3 border-t border-border-color">
                {{-- Status --}}
                <div x-show="showCols.status">
                   {{-- (កូដប៊ូតុង Status Toggle ដូចដើម) --}}
                </div>
                {{-- Actions --}}
                <div class="flex gap-2">
                   {{-- (កូដប៊ូតុង Edit និង Delete ដូចដើម) --}}
                </div>
            </div>
        </div>
    </div>
</template>