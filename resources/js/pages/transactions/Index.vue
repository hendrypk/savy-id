<script setup lang="ts">
import { 
    ArrowUpRightIcon, ArrowDownLeftIcon, 
    ArrowsRightLeftIcon, WalletIcon,PencilSquareIcon
} from '@heroicons/vue/24/outline';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import { route } from 'ziggy-js';
import { Link } from '@inertiajs/vue3';

defineProps<{
    groupedTransactions: Record<string, any[]>;
    stats: {
        total_balance: number;
        this_month_expense: number;
        this_month_income: number;
    }
}>();

const formatIDR = (val: number) => 
    new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);

const formatDate = (dateStr: string) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
};

// Map UI Color dari Type Enum
const getTypeStyles = (type: string) => {
    const maps: Record<string, { color: string, icon: any }> = {
        'income': { color: 'text-emerald-500 bg-emerald-50', icon: ArrowDownLeftIcon },
        'loan_disbursement': { color: 'text-emerald-600 bg-emerald-100', icon: ArrowDownLeftIcon },
        'expense': { color: 'text-rose-500 bg-rose-50', icon: ArrowUpRightIcon },
        'loan_repayment': { color: 'text-rose-600 bg-rose-100', icon: ArrowUpRightIcon },
        'saving': { color: 'text-indigo-500 bg-indigo-50', icon: ArrowsRightLeftIcon },
    };
    return maps[type] || { color: 'text-slate-500 bg-slate-50', icon: WalletIcon };
};
</script>

<template>
    <UserMobileLayout title="Transactions">
        <div class="max-w-md mx-auto pb-24">
            
            <div class="bg-slate-900 dark:bg-slate-800 rounded-3xl p-6 text-white shadow-xl mb-8 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60">Total Balance</p>
                    <h2 class="text-3xl font-black tracking-tighter mt-1">{{ formatIDR(stats.total_balance) }}</h2>
                    
                    <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-white/10">
                        <div>
                            <p class="text-[9px] font-bold uppercase opacity-50 mb-1">Income (Month)</p>
                            <p class="text-sm font-bold text-emerald-400">+ {{ formatIDR(stats.this_month_income) }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold uppercase opacity-50 mb-1">Expense (Month)</p>
                            <p class="text-sm font-bold text-rose-400">- {{ formatIDR(stats.this_month_expense) }}</p>
                        </div>
                    </div>
                </div>
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-3xl"></div>
            </div>

            <div v-for="(transactions, date) in groupedTransactions" :key="date" class="mb-8">
                <div class="flex justify-between items-center mb-3 px-2">
                    <h3 class="text-[11px] font-black uppercase text-slate-400 tracking-widest">{{ formatDate(date) }}</h3>
                </div>

                <div class="space-y-3">
                    <Link v-for="item in transactions" :key="item.uuid" 
                        :href="route('transactions.edit', item.uuid)"
                        class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-100 dark:border-slate-800 flex items-center gap-4 shadow-sm active:scale-[0.98] transition-all group"
                    >
                        
                        <div :class="getTypeStyles(item.type).color" class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0">
                            <component :is="getTypeStyles(item.type).icon" class="w-5 h-5 stroke-[2.5]" />
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col justify-between h-full">
                            <div class="flex justify-between items-start gap-3">
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 truncate flex-1">
                                    {{ item.description || item.category?.name }}
                                </h4>
                                <p :class="['text-sm font-black shrink-0', ['income', 'loan_disbursement'].includes(item.type) ? 'text-emerald-400' : 'text-rose-400 dark:text-rose-400']">
                                    {{ ['income', 'loan_disbursement'].includes(item.type) ? '+' : '-' }} {{ formatIDR(item.amount) }}
                                </p>
                            </div>
                            
                            <div class="flex justify-between items-end mt-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-[9px] font-black uppercase px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-500 rounded">
                                        {{ item.wallet?.name }}
                                    </span>
                                    <span v-if="item.reference" class="text-[9px] font-bold text-indigo-500 truncate italic">
                                        • {{ item.reference.name }}
                                    </span>
                                </div>
                                
                                <div class="p-1 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-100 dark:border-slate-700 opacity-60 group-active:scale-90 transition-transform">
                                    <PencilSquareIcon class="w-3 h-3 text-slate-500" />
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- <Fab :href="route('transactions.create')"  /> -->

        </div>
    </UserMobileLayout>
</template>