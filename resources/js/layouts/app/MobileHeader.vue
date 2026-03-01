<script setup lang="ts">
import { ChevronLeftIcon } from '@heroicons/vue/24/outline';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    firstName: string;
    isHome: boolean;
    showNav: boolean;
    backRoute?: string; // Isinya sekarang adalah URL String Lengkap
}>();

const goBackManual = () => {
    window.history.back();
};
</script>

<template>
    <header class="sticky top-0 z-40 w-full bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-100/50 dark:border-slate-800/50 px-4 h-16 flex items-center justify-between transition-all">
        
        <div class="flex items-center gap-2">
            <template v-if="!showNav">
                <Link 
                    v-if="backRoute"
                    :href="backRoute"
                    class="p-2 -ml-2 flex items-center justify-center text-slate-600 dark:text-slate-300 active:scale-90 transition-transform rounded-2xl bg-slate-50/50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700"
                >
                    <ChevronLeftIcon class="w-5 h-5 stroke-[3px]" />
                </Link>

                <button 
                    v-else
                    @click="goBackManual"
                    class="p-2 -ml-2 flex items-center justify-center text-slate-600 dark:text-slate-300 active:scale-90 transition-transform rounded-2xl bg-slate-50/50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700"
                >
                    <ChevronLeftIcon class="w-5 h-5 stroke-[3px]" />
                </button>
            </template>

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
            <slot name="right-action" />
        </div>
    </header>
</template>