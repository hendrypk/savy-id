<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { 
    ChevronLeftIcon, 
    EllipsisHorizontalIcon, 
    ArrowUpIcon, 
    ArrowDownIcon,
    PlusIcon,
    BanknotesIcon,
    BuildingLibraryIcon,
    CreditCardIcon,
    WalletIcon,
    PencilSquareIcon
} from '@heroicons/vue/24/outline';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import { ArrowRightLeftIcon } from 'lucide-vue-next';
import { route } from 'ziggy-js';

const props = defineProps<{
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
        
        <div v-if="wallet" class="space-y-6 pb-24 px-4 pt-4">
                    
            <div 
                class="relative w-full rounded-[3rem] p-6 shadow-2xl shadow-slate-200 dark:shadow-none overflow-hidden transition-all duration-500 group"
                :style="{ backgroundColor: wallet?.color || '#4f46e5' }"
                :class="getContrastColor(wallet?.color)"
            >
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-110 transition-transform"></div>
                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-black/10 rounded-full blur-3xl"></div>

                <div class="relative z-10 space-y-6">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-white/20 backdrop-blur-md rounded-2xl border border-white/10 shrink-0">
                                <component :is="getWalletIcon(wallet?.type)" class="w-5 h-5 stroke-[2.5px]" />
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-sm font-black uppercase tracking-widest opacity-90 leading-none mb-1.5 truncate">
                                    {{ wallet?.name }}
                                </h2>
                                <p class="text-[9px] font-bold opacity-60 uppercase tracking-tighter">{{ wallet?.type }}</p>
                            </div>
                        </div>
                        
                        <Link :href="route('wallets.edit', wallet.uuid)" class="p-2.5 bg-white/20 backdrop-blur-md rounded-xl border border-white/10 active:scale-90 transition-all">
                            <PencilSquareIcon class="w-4 h-4" />
                        </Link>
                    </div>

                    <div class="py-2">
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] opacity-60 mb-1">Total Saldo</p>
                        <p class="text-4xl font-black tracking-tighter leading-none break-words">
                            {{ formatCurrency(wallet?.balance) }}
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-2 pt-2">
                        <button class="flex flex-col items-center justify-center gap-2 p-3 bg-white/15 backdrop-blur-sm rounded-2xl border border-white/5 active:scale-95 transition-all group/btn">
                            <div class="p-2 bg-white/20 rounded-xl group-hover/btn:bg-white/30 transition-colors">
                                <PlusIcon class="w-5 h-5 stroke-[3px]" />
                            </div>
                            <span class="text-[8px] font-black uppercase tracking-tighter opacity-90">Add</span>
                        </button>

                        <button class="flex flex-col items-center justify-center gap-2 p-3 bg-white/15 backdrop-blur-sm rounded-2xl border border-white/5 active:scale-95 transition-all group/btn">
                            <div class="p-2 bg-white/20 rounded-xl group-hover/btn:bg-white/30 transition-colors">
                                <ArrowRightLeftIcon class="w-5 h-5 stroke-[3px]" />
                            </div>
                            <span class="text-[8px] font-black uppercase tracking-tighter opacity-90">Move</span>
                        </button>

                        <button class="flex flex-col items-center justify-center gap-2 p-3 bg-white/15 backdrop-blur-sm rounded-2xl border border-white/5 active:scale-95 transition-all group/btn">
                            <div class="p-2 bg-white/20 rounded-xl group-hover/btn:bg-white/30 transition-colors">
                                <CreditCardIcon class="w-5 h-5 stroke-[3px]" />
                            </div>
                            <span class="text-[8px] font-black uppercase tracking-tighter opacity-90">Pay</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between px-1">
                    <h3 class="font-black text-slate-400 dark:text-slate-500 uppercase text-[10px] tracking-[0.2em]">Riwayat Transaksi</h3>
                    <div class="h-px flex-1 bg-slate-100 dark:bg-slate-800 ml-4"></div>
                </div>

                <div v-if="transactions && transactions.length > 0" class="space-y-2">
                    <div v-for="tx in transactions" :key="tx.id" 
                        class="group flex items-center gap-4 p-4 rounded-3xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 active:scale-[0.98] transition-all">
                        
                        <div :class="[
                            'w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 shadow-sm',
                            tx.type === 'income' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'
                        ]">
                            <component :is="tx.type === 'income' ? ArrowDownIcon : ArrowUpIcon" class="w-5 h-5 stroke-[2.5px]" />
                        </div>

                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-slate-800 dark:text-white truncate tracking-tight">{{ tx.note || 'Tanpa Catatan' }}</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ formatDate(tx.created_at) }}</p>
                        </div>

                        <div class="text-right">
                            <p :class="['text-sm font-black tracking-tight', tx.type === 'income' ? 'text-emerald-600' : 'text-rose-600']">
                                {{ tx.type === 'income' ? '+' : '-' }}{{ formatCurrency(tx.amount).replace('Rp', '').trim() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div v-else class="py-16 text-center bg-slate-50/50 dark:bg-slate-800/50 rounded-[2.5rem] border border-dashed border-slate-200 dark:border-slate-700">
                    <div class="p-3 bg-white dark:bg-slate-900 w-fit mx-auto rounded-2xl shadow-sm mb-3">
                        <PlusIcon class="w-6 h-6 text-slate-300" />
                    </div>
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Belum ada aktivitas</p>
                </div>
            </div>
        </div>
    </UserMobileLayout>
</template>