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
    <header class="sticky top-0 z-40 w-full bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-100/50 dark:border-slate-800/50 px-4 h-16 flex items-center justify-between transition-all">
        
        <div class="flex items-center gap-2">
            <Link 
                v-if="!showNav"
                :href="backRoute ? route(backRoute) : route('back')"
                class="p-2 -ml-2 flex items-center justify-center text-slate-600 dark:text-slate-300 active:scale-90 transition-transform rounded-2xl bg-slate-50/50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700"
            >
                <ChevronLeftIcon class="w-5 h-5 stroke-[3px]" />
            </Link>

            <div class="flex flex-col">
                <h1 v-if="isHome" class="text-lg font-black text-slate-900 dark:text-white tracking-tight leading-none">
                    Halo, {{ firstName }}! 👋
                </h1>
                <h1 v-else-if="title" class="text-base font-bold text-slate-800 dark:text-slate-200 tracking-tight leading-none">
                    {{ title }}
                </h1>
            </div>
        </div>

        <div class="flex items-center">
            <button class="relative p-2.5 flex items-center justify-center text-slate-500 dark:text-slate-400 active:scale-95 transition-all rounded-2xl bg-slate-50/50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700">
                <BellIcon class="w-5 h-5 stroke-[2.2px]" />
                
                <span class="absolute top-2.5 right-2.5 flex h-2.5 w-2.5">
                    <span class="absolute inline-flex h-full w-full rounded-full bg-indigo-400"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-indigo-500 border-2 border-white dark:border-slate-900"></span>
                </span>
            </button>
        </div>
    </header>
</template>