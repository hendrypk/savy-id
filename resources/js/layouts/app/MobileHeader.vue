<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { ChevronLeftIcon, Cog6ToothIcon } from '@heroicons/vue/24/outline';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    firstName: string;
    isHome: boolean;
    showNav: boolean;
    backRoute?: string;
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
                <h1 v-if="isHome" class="text-xl font-black text-indigo-600 dark:text-indigo-400 tracking-tighter leading-none italic">
                    <div
                        class="mb-1 flex h-20 w-20 items-center justify-center rounded-md"
                    >
                        <AppLogoIcon
                            class="size-9 fill-current text-foreground dark:text-white"
                        />
                    </div>
                </h1>
                <h1 v-else-if="title" class="text-base font-bold text-slate-800 dark:text-slate-200 tracking-tight leading-none">
                    {{ title }}
                </h1>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <slot name="right-action" />

            <Link 
                v-if="showNav"
                href="/settings"
                class="flex items-center gap-2 p-1 pr-3 rounded-full bg-slate-100/50 dark:bg-slate-800/50 border border-slate-200/50 dark:border-slate-700/50 active:scale-95 transition-all group"
            >
                <div class="w-7 h-7 rounded-full bg-indigo-600 flex items-center justify-center shadow-sm">
                    <Cog6ToothIcon class="w-4 h-4 text-white stroke-[2.5px] group-hover:rotate-90 transition-transform duration-500" />
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">
                    {{ firstName }}
                </span>
            </Link>
        </div>
    </header>
</template>