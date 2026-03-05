<script setup lang="ts">
import { ref } from 'vue';
import { 
  HomeIcon as HomeOutline, ChartBarIcon as ChartOutline, 
  WalletIcon as WalletOutline, BanknotesIcon as LoansOutline, SparklesIcon as GoalsOutline,
  Bars3Icon, XMarkIcon
} from '@heroicons/vue/24/outline';
import { 
  HomeIcon as HomeSolid, ChartBarIcon as ChartSolid, 
  WalletIcon as WalletSolid, BanknotesIcon as LoansSolid, SparklesIcon as GoalsSolid 
} from '@heroicons/vue/24/solid';
import { Link } from '@inertiajs/vue3';

defineProps<{
    isHome: boolean;
    isBudget: boolean;
    isWallet: boolean;
    isSavings: boolean;
    isLoans: boolean;
}>();

const isExpanded = ref(false);

const toggleNav = () => {
    isExpanded.value = !isExpanded.value;
};

const getLinkStyles = (isActive: boolean) => [
    'flex flex-col items-center flex-1 transition-all duration-300 delay-150',
    isActive ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400',
];
</script>

<template>
    <div class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md z-[100] pb-safe pointer-events-none">
        <div class="p-6 flex justify-end items-end">
            
            <div 
                class="savy-nav-container shadow-2xl relative flex items-center bg-white/90 dark:bg-slate-900/95 backdrop-blur-xl border border-slate-200/50 dark:border-slate-800 transition-all duration-500 ease-[cubic-bezier(0.175,0.885,0.32,1.275)] pointer-events-auto overflow-hidden"
                :class="[isExpanded ? 'w-full h-[85px] rounded-[2.5rem] px-2' : 'w-16 h-16 rounded-full']"
            >
                <transition name="fade-slide">
                    <nav v-if="isExpanded" class="savy-nav-content flex justify-between items-center w-full px-4">
                        <Link href="/" :class="getLinkStyles(isHome)">
                            <component :is="isHome ? HomeSolid : HomeOutline" class="w-6 h-6" />
                            <span class="text-[9px] mt-1.5 font-black uppercase tracking-tighter">Home</span>
                        </Link>

                        <Link href="/budget" :class="getLinkStyles(isBudget)">
                            <component :is="isBudget ? ChartSolid : ChartOutline" class="w-6 h-6" />
                            <span class="text-[9px] mt-1.5 font-black uppercase tracking-tighter">Budget</span>
                        </Link>

                        <Link href="/wallets" :class="getLinkStyles(isWallet)">
                            <component :is="isWallet ? WalletSolid : WalletOutline" class="w-6 h-6" />
                            <span class="text-[9px] mt-1.5 font-black uppercase tracking-tighter">Wallet</span>
                        </Link>

                        <Link href="/loans" :class="getLinkStyles(isLoans)">
                            <component :is="isLoans ? LoansSolid : LoansOutline" class="w-6 h-6" />
                            <span class="text-[9px] mt-1.5 font-black uppercase tracking-tighter">Loans</span>
                        </Link>

                        <button @click="toggleNav" class="flex flex-col items-center flex-1 text-rose-500">
                            <div class="w-10 h-10 bg-rose-50 dark:bg-rose-950/30 rounded-full flex items-center justify-center active:scale-90 transition-all">
                                <XMarkIcon class="w-6 h-6 stroke-[2.5px]" />
                            </div>
                            <span class="text-[9px] mt-1 font-black uppercase opacity-60">Close</span>
                        </button>
                    </nav>
                </transition>

                <button 
                    v-if="!isExpanded" 
                    @click="toggleNav"
                    class="w-full h-full flex items-center justify-center bg-indigo-500 text-white active:scale-90 transition-all"
                >
                    <Bars3Icon class="w-7 h-7 stroke-[2.5px]" />
                </button>

            </div>
        </div>
    </div>
</template>

<style scoped>
.pb-safe {
    padding-bottom: env(safe-area-inset-bottom);
}

/* Animasi Fade & Slide untuk konten Nav */
.fade-slide-enter-active {
    transition: all 0.3s ease-out;
    transition-delay: 0.2s; /* Tunggu container melebar sedikit */
}
.fade-slide-leave-active {
    transition: all 0.2s ease-in;
}
.fade-slide-enter-from {
    opacity: 0;
    transform: translateY(10px);
}
.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(10px);
}

.savy-nav-container {
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
}
</style>