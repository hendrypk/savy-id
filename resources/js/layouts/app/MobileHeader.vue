<script setup lang="ts">
import { ChevronLeftIcon, BellIcon } from '@heroicons/vue/24/outline';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps<{
    title?: string;
    firstName: string;
    isHome: boolean;
    showNav: boolean;
    backRoute?: string;
}>();
</script>

<template>
    <header class="sticky top-0 z-20 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md px-4 py-3 flex justify-between items-center border-b border-slate-50 dark:border-slate-800">
        <div class="flex items-center gap-3">
            <Link 
                v-if="!showNav" 
                :href="backRoute ? route(backRoute) : route('back')"
                class="p-2 -ml-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors"
            >
                <ChevronLeftIcon class="w-6 h-6 stroke-[2.5px]" />
            </Link>

            <div>
                <p v-if="isHome" class="text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-extrabold leading-none mb-1">
                    Halo, {{ firstName }}
                </p>
                <h1 class="text-lg font-extrabold text-slate-800 dark:text-slate-100 leading-none">
                    {{ title || 'Ringkasan' }}
                </h1>
            </div>
        </div>

        <button class="relative p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-full transition-colors">
            <BellIcon class="w-6 h-6 stroke-[2px]" />
            <span class="absolute top-2 right-2.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-slate-900"></span>
        </button>
    </header>
</template>