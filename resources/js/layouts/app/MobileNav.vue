<script setup lang="ts">
import { 
  HomeIcon as HomeOutline, ChartBarIcon as ChartOutline, 
  WalletIcon as WalletOutline, Cog6ToothIcon as SettingsOutline, PlusIcon 
} from '@heroicons/vue/24/outline';
import { 
  HomeIcon as HomeSolid, ChartBarIcon as ChartSolid, 
  WalletIcon as WalletSolid, Cog6ToothIcon as SettingsSolid 
} from '@heroicons/vue/24/solid';
import { Link } from '@inertiajs/vue3';

defineProps<{
    isHome: boolean;
    isBudget: boolean;
    isWallet: boolean;
    isProfile: boolean;
}>();

const getLinkStyles = (isActive: boolean) => [
    'flex flex-col items-center flex-1 transition-all duration-300',
    isActive ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400'
];

const getLabelStyles = (isActive: boolean) => [
    'text-[9px] mt-1.5 font-black uppercase tracking-tighter transition-all duration-300',
    isActive ? 'opacity-100' : 'opacity-40'
];
</script>

<template>
    <div class="fixed bottom-6 left-1/2 -translate-x-1/2 w-[94%] max-w-md z-50">
        <nav class="bg-white/80 dark:bg-slate-900/90 backdrop-blur-xl border border-white/20 dark:border-slate-800/50 px-2 py-3 rounded-[2.5rem] flex justify-between items-end shadow-2xl">
            
            <Link href="/dashboard" :class="getLinkStyles(isHome)">
                <div class="relative flex items-center justify-center h-6 w-6">
                    <component :is="isHome ? HomeSolid : HomeOutline" class="w-6 h-6 active:scale-90" />
                    <div v-if="isHome" class="absolute -bottom-1 w-1 h-1 bg-current rounded-full"></div>
                </div>
                <span :class="getLabelStyles(isHome)">Home</span>
            </Link>

            <Link href="/budget" :class="getLinkStyles(isBudget)">
                <div class="relative flex items-center justify-center h-6 w-6">
                    <component :is="isBudget ? ChartSolid : ChartOutline" class="w-6 h-6 active:scale-90" />
                    <div v-if="isBudget" class="absolute -bottom-1 w-1 h-1 bg-current rounded-full"></div>
                </div>
                <span :class="getLabelStyles(isBudget)">Budget</span>
            </Link>

            <Link href="/transactions/create" class="flex flex-col items-center flex-1 -mt-10 mb-1 transition-transform active:scale-90">
                <div class="bg-linear-to-tr from-indigo-600 to-indigo-500 p-4 rounded-full shadow-lg border-[6px] border-slate-50 dark:border-slate-950">
                    <PlusIcon class="w-6 h-6 text-white stroke-[3.5px]" />
                </div>
                <span class="text-[9px] mt-1 font-black uppercase tracking-tighter text-indigo-600 dark:text-indigo-400">Catat</span>
            </Link>

            <Link href="/wallets" :class="getLinkStyles(isWallet)">
                <div class="relative flex items-center justify-center h-6 w-6">
                    <component :is="isWallet ? WalletSolid : WalletOutline" class="w-6 h-6 active:scale-90" />
                    <div v-if="isWallet" class="absolute -bottom-1 w-1 h-1 bg-current rounded-full"></div>
                </div>
                <span :class="getLabelStyles(isWallet)">Wallet</span>
            </Link>

            <Link href="/settings" :class="getLinkStyles(isProfile)">
                <div class="relative flex items-center justify-center h-6 w-6">
                    <component :is="isProfile ? SettingsSolid : SettingsOutline" class="w-6 h-6 active:scale-90" />
                    <div v-if="isProfile" class="absolute -bottom-1 w-1 h-1 bg-current rounded-full"></div>
                </div>
                <span :class="getLabelStyles(isProfile)">Setting</span>
            </Link>

        </nav>
    </div>
</template>