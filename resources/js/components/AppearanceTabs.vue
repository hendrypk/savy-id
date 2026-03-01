<script setup lang="ts">
import { Monitor, Moon, Sun } from 'lucide-vue-next';
import { useAppearance } from '@/composables/useAppearance';

const { appearance, updateAppearance } = useAppearance();

const tabs = [
    { value: 'light', Icon: Sun, label: 'Light' },
    { value: 'dark', Icon: Moon, label: 'Dark' },
    { value: 'system', Icon: Monitor, label: 'System' },
] as const;
</script>

<template>
    <div class="space-y-3">
        
        <div
            class="flex w-full gap-1.5 rounded-3xl bg-slate-100/80 p-1.5 dark:bg-slate-800/50 backdrop-blur-sm"
        >
            <button
                v-for="{ value, Icon, label } in tabs"
                :key="value"
                @click="updateAppearance(value)"
                :class="[
                    'flex-1 flex items-center justify-center rounded-[1.2rem] py-3 transition-all duration-300',
                    appearance === value
                        ? 'bg-white text-indigo-600 shadow-lg shadow-slate-200 dark:bg-slate-700 dark:text-indigo-400 dark:shadow-none scale-[1.02]'
                        : 'text-slate-500 active:scale-95 dark:text-slate-400',
                ]"
            >
                <component :is="Icon" class="h-4 w-4" />
                <span class="ml-2 text-xs font-bold">{{ label }}</span>
            </button>
        </div>
    </div>
</template>