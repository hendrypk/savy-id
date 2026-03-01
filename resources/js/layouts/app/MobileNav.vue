<script setup lang="ts">
import { PlusIcon } from '@heroicons/vue/24/outline';
import { 
  HomeIcon as HomeOutline, ChartBarIcon as ChartOutline, 
  WalletIcon as WalletOutline 
} from '@heroicons/vue/24/outline';
import { 
  HomeIcon as HomeSolid, ChartBarIcon as ChartSolid, 
  WalletIcon as WalletSolid 
} from '@heroicons/vue/24/solid';
import { Link } from '@inertiajs/vue3';
import { Settings, Settings2Icon } from 'lucide-vue-next';

defineProps<{
    isHome: boolean;
    isBudget: boolean;
    isWallet: boolean;
    isProfile: boolean;
    activeClass: string;
    inactiveClass: string;
}>();

</script>

<template>
    <div class="fixed bottom-6 left-1/2 -translate-x-1/2 w-[92%] max-w-md z-50">
        <nav class="bg-white/80 dark:bg-slate-900/90 backdrop-blur-xl border border-white/20 dark:border-slate-800/50 px-2 py-3 rounded-[2.5rem] flex justify-between items-center shadow-[0_20px_50px_rgba(0,0,0,0.1)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.3)]">
            
            <Link href="/dashboard" class="flex flex-col items-center flex-1 transition-all duration-300" :class="isHome ? activeClass : inactiveClass">
                <div class="relative group">
                    <HomeSolid v-if="isHome" class="w-6 h-6" />
                    <HomeOutline v-else class="w-6 h-6 group-active:scale-90" />
                    <div v-if="isHome" class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-indigo-600 rounded-full"></div>
                </div>
                <span class="text-[9px] mt-1 font-extrabold uppercase tracking-tighter" :class="isHome ? 'opacity-100' : 'opacity-0'">Home</span>
            </Link>

            <Link href="/budget" class="flex flex-col items-center flex-1 transition-all duration-300" :class="isBudget ? activeClass : inactiveClass">
                <div class="relative group">
                    <ChartSolid v-if="isBudget" class="w-6 h-6" />
                    <ChartOutline v-else class="w-6 h-6 group-active:scale-90" />
                    <div v-if="isBudget" class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-indigo-600 rounded-full"></div>
                </div>
                <span class="text-[9px] mt-1 font-extrabold uppercase tracking-tighter" :class="isBudget ? 'opacity-100' : 'opacity-0'">Budget</span>
            </Link>

            <Link href="/transactions/create" class="flex flex-col items-center flex-1 -mt-14 transition-transform active:scale-90">
                <div class="bg-linear-to-tr from-indigo-600 to-indigo-500 p-4 rounded-full shadow-lg border-[6px] border-slate-50 dark:border-slate-950">
                    <PlusIcon class="w-7 h-7 text-white stroke-[3px]" />
                </div>
                <span class="text-[10px] mt-1 font-bold text-indigo-600 dark:text-indigo-400">Catat</span>
            </Link>

            <Link href="/wallets" class="flex flex-col items-center flex-1 transition-all duration-300" :class="isWallet ? activeClass : inactiveClass">
                <div class="relative group">
                    <WalletSolid v-if="isWallet" class="w-6 h-6" />
                    <WalletOutline v-else class="w-6 h-6 group-active:scale-90" />
                    <div v-if="isWallet" class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-indigo-600 rounded-full"></div>
                </div>
                <span class="text-[9px] mt-1 font-extrabold uppercase tracking-tighter" :class="isWallet ? 'opacity-100' : 'opacity-0'">Wallet</span>
            </Link>

            <Link href="/settings" class="flex flex-col items-center flex-1 transition-all duration-300" :class="isProfile ? activeClass : inactiveClass">
                <div class="relative group">
                    <Settings v-if="isProfile" class="w-6 h-6" />
                    <Settings2Icon v-else class="w-6 h-6 group-active:scale-90" />
                    <div v-if="isProfile" class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-indigo-600 rounded-full"></div>
                </div>
                <span class="text-[9px] mt-1 font-extrabold uppercase tracking-tighter" :class="isProfile ? 'opacity-100' : 'opacity-0'">Setting</span>
            </Link>

        </nav>
    </div>
</template>