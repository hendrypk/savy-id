<script setup lang="ts">
import { 
  ArrowUpRightIcon,
  ShoppingBagIcon,
  PencilSquareIcon,
  LockClosedIcon,
  CalendarIcon,
  WalletIcon
} from '@heroicons/vue/24/outline';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';

const props = defineProps<{
  budget: any;
  transactions: any[];
}>();

const isEditable = computed(() => {
    const now = new Date();
    const currentMonthLocal = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`; 
    return props.budget.month_raw === currentMonthLocal;
});

const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(value);
};

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'short'
  });
};
</script>

<template>
  <Head :title="'Overview ' + budget.name" />

  <UserMobileLayout :title="budget.name" :back-route="route('budget.index')">
    <div class="space-y-8 pb-24 px-1">
      
      <div class="space-y-6">
        <div class="flex justify-between items-end">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Current Spending</p>
                <h2 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">
                    {{ formatCurrency(budget.spent_amount) }}
                </h2>
            </div>
            
            <Link v-if="isEditable" :href="route('budget.edit', budget.uuid)" 
                class="mb-1 p-3 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-2xl active:scale-90 transition-all">
                <PencilSquareIcon class="w-5 h-5" />
            </Link>
            <div v-else class="mb-1 p-3 bg-slate-100 dark:bg-slate-800 text-slate-400 rounded-2xl">
                <LockClosedIcon class="w-5 h-5" />
            </div>
        </div>

        <div class="space-y-3">
            <div class="h-4 w-full bg-slate-100 dark:bg-slate-800 rounded-full p-1 border border-slate-200/50 dark:border-slate-700">
                <div class="h-full rounded-full bg-linear-to-r from-indigo-500 to-purple-500 transition-all duration-1000 shadow-lg shadow-indigo-500/20"
                    :style="{ width: Math.min(budget.percentage, 100) + '%' }">
                </div>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-[11px] font-bold text-slate-500 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    {{ budget.percentage }}% Consumed
                </span>
                <span class="text-[11px] font-black text-slate-900 dark:text-slate-100 uppercase italic">
                    Limit: {{ formatCurrency(budget.plan_amount) }}
                </span>
            </div>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div class="bg-white dark:bg-slate-800 p-4 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm">
            <CalendarIcon class="w-4 h-4 text-indigo-500 mb-2" />
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Periode</p>
            <p class="text-xs font-bold text-slate-700 dark:text-slate-200">{{ budget.month_label }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm">
            <WalletIcon class="w-4 h-4 text-emerald-500 mb-2" />
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Sisa Budget</p>
            <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ formatCurrency(budget.remaining) }}</p>
        </div>
      </div>

      <div class="space-y-5">
        <h3 class="font-black text-[10px] uppercase tracking-[0.2em] text-slate-400 flex items-center gap-2">
            Riwayat Transaksi 
            <span class="h-px flex-1 bg-slate-100 dark:bg-slate-800"></span>
        </h3>
        
        <div v-if="transactions.length === 0" class="py-12 text-center opacity-50">
           <ShoppingBagIcon class="w-10 h-10 mx-auto mb-2 text-slate-300" />
           <p class="text-[10px] font-bold uppercase tracking-widest">No transactions yet</p>
        </div>

        <div class="space-y-0 border-l-2 border-slate-50 dark:border-slate-800 ml-3 pl-6">
            <div v-for="tx in transactions" :key="tx.id" class="relative mb-8 last:mb-0">
                <div class="absolute -left-7.75 top-1 w-2 h-2 rounded-full bg-white dark:bg-slate-900 border-2 border-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.5)]"></div>
                
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-rose-500 uppercase tracking-tighter mb-0.5">
                            -{{ formatCurrency(tx.amount) }}
                        </p>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-tight">
                            {{ tx.description || 'General Expense' }}
                        </h4>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[10px] font-medium text-slate-400">{{ formatDate(tx.transaction_date) }}</span>
                            <span class="text-[10px] text-slate-300">•</span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">{{ tx.wallet?.name }}</span>
                        </div>
                    </div>
                    <div class="p-2 bg-slate-50 dark:bg-slate-800 rounded-xl">
                        <ArrowUpRightIcon class="w-4 h-4 text-slate-400" />
                    </div>
                </div>
            </div>
        </div>
      </div>

    </div>
  </UserMobileLayout>
</template>