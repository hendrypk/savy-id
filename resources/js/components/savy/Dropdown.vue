<script setup lang="ts">
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'

defineProps<{
    label?: string;
    activeName: string;
    items: Array<{ id: number | string; name: string }>;
    error?: string;
    icon?: any;
}>();

defineEmits(['select']);
</script>

<template>
    <div class="space-y-1.5">
        <label v-if="label" class="text-[10px] font-black uppercase text-slate-400 ml-1 flex items-center gap-2">
            <component :is="icon" v-if="icon" class="w-3.5 h-3.5" />
            {{ label }}
        </label>

        <Menu as="div" class="relative inline-block w-full text-left">
            <div>
                <MenuButton class="w-full h-14 px-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 flex items-center justify-between active:scale-[0.98] transition-all shadow-sm">
                    <span :class="activeName !== 'Pilih' ? 'text-slate-900 dark:text-white font-bold text-sm' : 'text-slate-400 text-sm'">
                        {{ activeName }}
                    </span>
                    <ChevronDownIcon class="w-5 h-5 text-slate-400 transition-transform ui-open:rotate-180" aria-hidden="true" />
                </MenuButton>
            </div>

            <transition
                enter-active-class="transition duration-100 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-75 ease-in"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
            >
                <MenuItems class="absolute left-0 mt-2 w-full origin-top-left divide-y divide-slate-100 dark:divide-slate-800 rounded-2xl bg-white dark:bg-slate-900 shadow-2xl ring-1 ring-black/5 focus:outline-none z-50 overflow-hidden border border-slate-100 dark:border-slate-800">
                    <div class="p-1.5 max-h-60 overflow-y-auto no-scrollbar">
                        <MenuItem v-for="item in items" :key="item.id" v-slot="{ active }">
                            <button
                                type="button"
                                @click="$emit('select', item)"
                                :class="[
                                    active ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-slate-700 dark:text-slate-300',
                                    'group flex w-full items-center rounded-xl px-4 py-3.5 text-xs font-black uppercase tracking-tight transition-colors'
                                ]"
                            >
                                {{ item.name }}
                            </button>
                        </MenuItem>
                    </div>
                </MenuItems>
            </transition>
        </Menu>

        <p v-if="error" class="text-[9px] font-bold text-rose-500 uppercase ml-1 tracking-tight">{{ error }}</p>
    </div>
</template>