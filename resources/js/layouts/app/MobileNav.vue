<script setup lang="ts">
import { 
  HomeIcon as HomeOutline, ChartBarIcon as ChartOutline, 
  WalletIcon as WalletOutline, BanknotesIcon as LoansOutline,
  Bars3Icon, XMarkIcon
} from '@heroicons/vue/24/outline';
import { 
  HomeIcon as HomeSolid, ChartBarIcon as ChartSolid, 
  WalletIcon as WalletSolid, BanknotesIcon as LoansSolid 
} from '@heroicons/vue/24/solid';
import { Link } from '@inertiajs/vue3';
import { isNavExpandedGlobal } from '@/stores/navState';

const isExpanded = isNavExpandedGlobal;

defineProps<{
    isHome: boolean;
    isBudget: boolean;
    isWallet: boolean;
    isSavings: boolean;
    isLoans: boolean;
}>();

const toggleNav = () => {
    isExpanded.value = !isExpanded.value;
};

const getLinkStyles = (isActive: boolean) => [
    'flex flex-col items-center flex-1 transition-all duration-300 delay-150',
    isActive ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400',
];
</script>

<template>
    <div class="fixed bottom-0 right-0 w-full z-[100] pb-safe pointer-events-none">
        <div :class="['p-6 flex items-end transition-all duration-500', isExpanded ? 'justify-center' : 'justify-end']">
            
            <div 
                class="savy-nav-container shadow-2xl transition-all duration-500 ease-[cubic-bezier(0.175,0.885,0.32,1.275)] pointer-events-auto"
                :class="[isExpanded ? 'w-full max-w-md h-[80px] rounded-[2.5rem]' : 'w-16 h-16 rounded-full']"
            >
                <div class="savy-nav-anim-layer"></div>

                <div class="savy-nav-content overflow-hidden" :class="[isExpanded ? 'rounded-[2.4rem]' : 'rounded-full']">
                    
                    <transition name="fade-slide">
                        <nav v-if="isExpanded" class="flex justify-between items-center w-full h-full px-4">
                            <Link href="/" :class="getLinkStyles(isHome)">
                                <component :is="isHome ? HomeSolid : HomeOutline" class="w-6 h-6" />
                                <span class="text-[9px] mt-1.5 font-bold uppercase tracking-tighter">Home</span>
                            </Link>

                            <Link href="/budget" :class="getLinkStyles(isBudget)">
                                <component :is="isBudget ? ChartSolid : ChartOutline" class="w-6 h-6" />
                                <span class="text-[9px] mt-1.5 font-bold uppercase tracking-tighter">Budget</span>
                            </Link>

                            <button @click="toggleNav" class="flex flex-col items-center flex-1 text-rose-500">
                                <div class="w-10 h-10 bg-rose-50 dark:bg-rose-900/30 rounded-full flex items-center justify-center active:scale-90 transition-all">
                                    <XMarkIcon class="w-6 h-6 stroke-[3px]" />
                                </div>
                                <span class="text-[9px] mt-1 font-black uppercase opacity-60">Close</span>
                            </button>

                            <Link href="/wallets" :class="getLinkStyles(isWallet)">
                                <component :is="isWallet ? WalletSolid : WalletOutline" class="w-6 h-6" />
                                <span class="text-[9px] mt-1.5 font-bold uppercase tracking-tighter">Wallet</span>
                            </Link>

                            <Link href="/loans" :class="getLinkStyles(isLoans)">
                                <component :is="isLoans ? LoansSolid : LoansOutline" class="w-6 h-6" />
                                <span class="text-[9px] mt-1.5 font-bold uppercase tracking-tighter">Loans</span>
                            </Link>
                        </nav>
                    </transition>

                    <button 
                        v-if="!isExpanded" 
                        @click="toggleNav"
                        class="w-full h-full flex items-center justify-center text-indigo-500 dark:text-indigo-400 active:scale-90 transition-all z-20"
                    >
                        <Bars3Icon class="w-7 h-7 stroke-[2.5px]" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
