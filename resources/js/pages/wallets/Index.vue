<script setup lang="ts">
import { 
    WalletIcon, 
    CreditCardIcon, 
    BanknotesIcon,
    PlusIcon 
} from '@heroicons/vue/24/outline';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';

const props = defineProps<{ wallets: any[] }>();

const totalBalance = computed(() => {
    return props.wallets.reduce((acc, wallet) => acc + Number(wallet.balance), 0);
});

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('id-ID', { 
        style: 'currency', 
        currency: 'IDR', 
        minimumFractionDigits: 0 
    }).format(value);
};

const getWalletIcon = (type: string) => {
    if (type === 'bank') return CreditCardIcon;
    if (type === 'cash') return BanknotesIcon;
    return WalletIcon;
};

</script>

<template>
    <Head title="Dompet Saya" />

    <UserMobileLayout title="Dompet">
        <div class="space-y-6 pb-24 px-1">
            
            <div class="bg-indigo-600 dark:bg-indigo-500 p-6 rounded-[2.5rem] text-white shadow-xl shadow-indigo-200 dark:shadow-none relative overflow-hidden flex justify-between items-center">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-1">Total Saldo Seluruhnya</p>
                    <h2 class="text-2xl font-black tracking-tighter">
                        {{ formatCurrency(totalBalance) }}
                    </h2>
                </div>

                <Link :href="route('wallets.create')" class="relative z-10 bg-white/20 hover:bg-white/30 p-3 rounded-2xl backdrop-blur-md transition-all active:scale-90 border border-white/20">
                    <PlusIcon class="w-6 h-6 stroke-[3px]" />
                </Link>
            </div>

            <div v-if="wallets.length > 0" class="flex justify-between items-center px-2">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    Daftar Dompet ({{ wallets.length }})
                </p>
            </div>
            <div class="grid grid-cols-2 gap-3 px-1">
                <Link 
                    v-for="wallet in wallets" 
                    :key="wallet.uuid" 
                    :href="route('wallets.show', wallet.uuid)"
                    class="group relative h-25 p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm active:scale-[0.95] transition-all duration-300 overflow-hidden flex flex-col"
                >
                    <div class="flex items-start justify-between gap-2 mb-1">
                        <div 
                            class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center bg-slate-50 dark:bg-slate-800 transition-transform group-hover:scale-110"
                            :style="{ color: wallet.color }"
                        >
                            <component :is="getWalletIcon(wallet.type)" class="w-5 h-5 stroke-[2px]" />
                        </div>

                        <div class="flex flex-col items-end min-w-0 flex-1">
                            <h3 class="text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-tight truncate w-full text-right">
                                {{ wallet.name }}
                            </h3>
                            <span class="mt-1 text-[7px] font-medium capitalize tracking-widest text-slate-400 bg-slate-50 dark:bg-slate-800 px-2 py-0.5 rounded-full whitespace-nowrap">
                                {{ wallet.type }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-auto">
                        <p class="text-lg font-black text-slate-900 dark:text-white tracking-[-0.05em] leading-tight wrap-break-words line-clamp-2">
                            {{ formatCurrency(wallet.balance) }}
                        </p>
                    </div>

                    <div 
                        class="absolute bottom-0 left-6 right-6 h-1 rounded-t-full opacity-30"
                        :style="{ backgroundColor: wallet.color }"
                    ></div>
                </Link>
            </div>
            <div v-if="wallets.length === 0" class="py-12 flex flex-col items-center">
                <div class="bg-slate-50 dark:bg-slate-800/50 p-10 rounded-[3rem] border border-dashed border-slate-200 dark:border-slate-700 flex flex-col items-center w-full">
                    <WalletIcon class="w-12 h-12 text-slate-300 mb-4" />
                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Belum ada dompet</p>
                </div>
            </div>
        </div>
    </UserMobileLayout>
</template>