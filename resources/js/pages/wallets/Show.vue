<script setup lang="ts">
import { 
    ArrowUpIcon, 
    ArrowDownIcon,
    PlusIcon,
    BanknotesIcon,
    BuildingLibraryIcon,
    CreditCardIcon,
    WalletIcon,
    PencilSquareIcon
} from '@heroicons/vue/24/outline';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRightLeftIcon } from 'lucide-vue-next';
import { route } from 'ziggy-js';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';

defineProps<{
    wallet: any;
    transactions?: any[];
}>();

// Helper: Menentukan apakah warna background terang atau gelap
// Jika terang, gunakan teks hitam. Jika gelap, gunakan teks putih.
const getContrastColor = (hex: string) => {
    if (!hex) return 'text-white';
    const r = parseInt(hex.slice(1, 3), 16);
    const g = parseInt(hex.slice(3, 5), 16);
    const b = parseInt(hex.slice(5, 7), 16);
    const brightness = (r * 299 + g * 587 + b * 114) / 1000;
    return brightness > 155 ? 'text-slate-900' : 'text-white';
};

const getWalletIcon = (type: string) => {
    if (!type) return WalletIcon;
    const t = type.toLowerCase();
    if (t.includes('cash') || t.includes('tunai')) return BanknotesIcon;
    if (t.includes('bank')) return BuildingLibraryIcon;
    if (t.includes('card') || t.includes('cc') || t.includes('kredit')) return CreditCardIcon;
    return WalletIcon;
};

const formatCurrency = (value: number) => {
    if (value === undefined || value === null) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value);
};

const formatDate = (dateString: string) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
};
</script>

<template>
    <Head :title="wallet?.name ? `Detail ${wallet.name}` : 'Detail Dompet'" />

    <UserMobileLayout 
        :title="wallet?.name || 'Detail'" 
        :back-route="route('wallets.index')"
    >
        <div v-if="wallet" class="space-y-8 pb-24 px-5 pt-6">
            
            <Card>
                <CardContent class="relative z-10">
                    <div class="flex justify-between items-start mb-10">
                        <div class="flex items-center gap-4">
                            <div 
                                class="flex items-center justify-center w-14 h-14 rounded-2xl shadow-lg shadow-inner"
                                :style="{ backgroundColor: wallet?.color + '20' }" 
                            > <component 
                                    :is="getWalletIcon(wallet?.type)" 
                                    class="w-7 h-7 stroke-[2]"
                                    :style="{ color: wallet?.color || '#4f46e5' }"
                                />
                            </div>
                            <div>
                                <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-0.5">
                                    {{ wallet?.type }}
                                </h2>
                                <p class="text-lg font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
                                    {{ wallet?.name }}
                                </p>
                            </div>
                        </div>
                        
                        <Link :href="route('wallets.edit', wallet.uuid)" class="p-2.5 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 rounded-xl transition-colors">
                            <PencilSquareIcon class="w-5 h-5 text-slate-400" />
                        </Link>
                    </div>

                    <div class="space-y-1 mb-8">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em]">Total Balance</span>
                        <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">
                            {{ formatCurrency(wallet?.balance) }}
                        </h3>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <button class="flex items-center justify-center gap-2 py-3.5 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-2xl border border-slate-100 dark:border-slate-700 transition-all active:scale-95 group">
                            <PlusIcon class="w-4 h-4 text-slate-600 dark:text-slate-300 stroke-[3px]" />
                            <span class="text-[10px] font-black text-slate-600 dark:text-slate-300 uppercase tracking-wider">Add</span>
                        </button>
                        <button class="flex items-center justify-center gap-2 py-3.5 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-2xl border border-slate-100 dark:border-slate-700 transition-all active:scale-95">
                            <ArrowRightLeftIcon class="w-4 h-4 text-slate-600 dark:text-slate-300 stroke-[3px]" />
                            <span class="text-[10px] font-black text-slate-600 dark:text-slate-300 uppercase tracking-wider">Move</span>
                        </button>
                        <button class="flex items-center justify-center gap-2 py-3.5 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-2xl border border-slate-100 dark:border-slate-700 transition-all active:scale-95">
                            <CreditCardIcon class="w-4 h-4 text-slate-600 dark:text-slate-300 stroke-[3px]" />
                            <span class="text-[10px] font-black text-slate-600 dark:text-slate-300 uppercase tracking-wider">Pay</span>
                        </button>
                    </div>
                </CardContent>
            </Card>

            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="font-black text-slate-900 dark:text-white uppercase text-[11px] tracking-[0.15em]">Recent Activity</h3>
                    <Link href="#" class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider">View All</Link>
                </div>

                <div v-if="transactions && transactions.length > 0" class="space-y-3">
                    <div v-for="tx in transactions" :key="tx.id" 
                        class="flex items-center gap-4 p-4 rounded-[1.75rem] bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 transition-all active:bg-slate-50">
                        
                        <div :class="[
                            'w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-sm transition-transform group-active:scale-90',
                            tx.type === 'income' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'
                        ]">
                            <component :is="tx.type === 'income' ? ArrowDownIcon : ArrowUpIcon" class="w-5 h-5 stroke-[2.5px]" />
                        </div>

                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 truncate tracking-tight">
                                {{ tx.description || tx.category?.name || 'Transaksi' }}
                            </h4>
                            <p class="text-[11px] font-medium text-slate-400 uppercase tracking-tight">{{ formatDate(tx.transaction_date || tx.created_at) }}</p>
                        </div>

                        <div class="text-right">
                            <p :class="['text-sm font-black tracking-tight', tx.type === 'income' ? 'text-emerald-600' : 'text-rose-600']">
                                {{ tx.type === 'income' ? '+' : '-' }}{{ formatCurrency(tx.amount).replace('Rp', '').trim() }}
                            </p>
                            <p class="text-[9px] font-bold text-slate-300 dark:text-slate-600 uppercase tracking-widest">IDR</p>
                        </div>
                    </div>
                </div>

                <div v-else class="py-20 text-center bg-slate-50/50 dark:bg-slate-800/40 rounded-[2.5rem] border-2 border-dashed border-slate-200 dark:border-slate-800">
                    <div class="inline-flex p-4 bg-white dark:bg-slate-900 rounded-3xl shadow-sm mb-4">
                        <WalletIcon class="w-8 h-8 text-slate-200" />
                    </div>
                    <p class="text-slate-400 text-[11px] font-black uppercase tracking-[0.2em]">No transactions yet</p>
                </div>
            </div>
        </div>
    </UserMobileLayout>
</template>