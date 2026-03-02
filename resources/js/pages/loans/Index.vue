<script setup lang="ts">
import { 
  BanknotesIcon, 
  CalendarDaysIcon, 
  EyeIcon
} from '@heroicons/vue/24/outline';
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js'; 
import Button from '@/components/ui/button/Button.vue';
import Fab from '@/components/ui/button/Fab.vue';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';

interface Loan {
  uuid: string;
  name: string;
  remaining_amount: number;
  monthly_installment: number;
  total_tenor: number;
  progress: number;
  status: string;
  due_date?: number | string;
  times_paid: number;
  remaining_tenor: number;
}

defineProps<{
  loans: Loan[];
  totalDebt: number;
  totalMonthly: number;
}>();

const formatCurrency = (value: number | string | null) => {
  const amount = Number(value || 0);
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(amount);
};
</script>

<template>
  <Head title="My Loans" />

  <UserMobileLayout title="My Loans">
    <div class="space-y-6 pb-32"> 
      
      <div class="grid grid-cols-2 gap-3 px-1">
        <div class="bg-rose-50/50 dark:bg-rose-500/10 p-4 rounded-[2rem] border border-rose-100 dark:border-rose-500/20 shadow-sm">
          <div class="flex items-center gap-1.5 mb-1 text-rose-400">
            <BanknotesIcon class="w-3 h-3 stroke-[2.5]" />
            <span class="text-[8px] font-black uppercase tracking-widest">Total Debt</span>
          </div>
          <p class="text-sm font-black text-rose-600 dark:text-rose-400 font-mono truncate">
            {{ formatCurrency(totalDebt) }}
          </p>
        </div>

        <div class="bg-indigo-50/50 dark:bg-indigo-500/10 p-4 rounded-[2rem] border border-indigo-100 dark:border-indigo-500/20 shadow-sm">
          <div class="flex items-center gap-1.5 mb-1 text-indigo-400">
            <CalendarDaysIcon class="w-3 h-3 stroke-[2.5]" />
            <span class="text-[8px] font-black uppercase tracking-widest">Monthly</span>
          </div>
          <p class="text-sm font-black text-indigo-700 dark:text-indigo-400 font-mono truncate">
            {{ formatCurrency(totalMonthly) }}
          </p>
        </div>
      </div>

      <div class="space-y-4">
        <div v-if="loans.length > 0" class="px-2 flex justify-between items-center">
          <h3 class="font-black text-[9px] uppercase tracking-[0.2em] text-slate-400">
            {{ loans.length }} Active Accounts
          </h3>
        </div>

        <div class="space-y-4">
          <div 
            v-for="loan in loans" 
            :key="loan.uuid"
            class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.2rem] overflow-hidden transition-all active:scale-[0.98] shadow-sm"
          >
            <div class="p-5">
              <div class="flex justify-between items-center mb-5">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-indigo-600 flex items-center justify-center text-white rounded-xl shadow-lg shadow-indigo-100 dark:shadow-none">
                    <BanknotesIcon class="w-5 h-5 stroke-[2.5]" />
                  </div>
                  <div>
                    <h4 class="text-xs font-black text-slate-800 dark:text-slate-100 leading-none capitalize">
                      {{ loan.name }}
                    </h4>
                    <div class="flex items-center gap-1 mt-1.5">
                      <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                      <span class="text-[7px] text-slate-400 font-black uppercase tracking-tighter">
                        {{ loan.status }}
                      </span>
                    </div>
                  </div>
                </div>
                <div class="flex justify-between items-center mb-5">
                  <Link 
                    :href="route('loans.show', loan.uuid)" 
                    class="p-2 -mr-2 text-slate-300 active:text-indigo-500 transition-colors"
                  >
                    <EyeIcon class="w-5 h-5 stroke-[2]" />
                  </Link>
                </div>
              </div>

              <div class="mb-5 px-0.5">
                <div class="flex justify-between text-[8px] mb-2 font-black uppercase tracking-[0.1em]">
                  <span class="text-slate-400 font-bold">Payoff Progress</span>
                  <span class="text-indigo-600 font-mono">{{ loan.progress }}%</span>
                </div>
                <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                  <div 
                    class="h-full bg-indigo-600 rounded-full transition-all duration-1000 ease-out"
                    :style="{ width: loan.progress + '%' }"
                  ></div>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-50 dark:border-slate-800/50 mb-4">
                <div>
                  <p class="text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Remaining Amount</p>
                  <p class="text-[11px] font-black text-slate-800 dark:text-white font-mono uppercase">
                    {{ formatCurrency(loan.remaining_amount) }}
                  </p>
                </div>
                <div class="text-right">
                  <p class="text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Installment Status</p>
                  <div class="flex items-center justify-end gap-1.5">
                    <span class="text-[11px] font-black text-slate-800 dark:text-white font-mono">
                      {{ loan.times_paid }} <span class="text-slate-300 dark:text-slate-600">/</span> {{ loan.total_tenor }}
                    </span>
                    <span class="text-[8px] font-black text-indigo-500 uppercase italic">Times</span>
                  </div>
                  <p v-if="loan.remaining_tenor > 0" class="text-[8px] font-bold text-slate-400 mt-0.5 tracking-tight uppercase">
                    {{ loan.remaining_tenor }} months left
                  </p>
                </div>
              </div>

              <div class="flex justify-between items-center p-3 bg-slate-50 dark:bg-slate-900/40 rounded-xl border border-slate-100/50 dark:border-slate-800/50">
                <div class="flex items-center gap-1.5 text-slate-400 font-bold">
                  <CalendarDaysIcon class="w-3.5 h-3.5" />
                  <span class="text-[9px] uppercase">Due: Day {{ loan.due_date || '-' }}</span>
                </div>
                <div class="text-right">
                  <span class="text-[11px] font-black text-indigo-600 dark:text-indigo-400 font-mono">
                    {{ formatCurrency(loan.monthly_installment) }}
                  </span>
                  <span class="text-[7px] font-black text-slate-400 ml-1">/MO</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-if="loans.length === 0" class="py-24 flex flex-col items-center text-center px-10">
          <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800/50 rounded-[2.5rem] flex items-center justify-center mb-6 border border-slate-100 dark:border-slate-800">
            <BanknotesIcon class="w-8 h-8 text-slate-300" />
          </div>
          <h3 class="font-black text-slate-800 dark:text-slate-100 text-lg">No Active Loans</h3>
          <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-widest leading-relaxed">
            Your financial path is clear. Add a loan to start tracking your repayment progress.
          </p>
          <Link :href="route('loans.create')" class="mt-8">
            <Button variant="purple" class="rounded-full px-8 h-12 font-black uppercase text-[10px] tracking-[0.15em] shadow-lg shadow-indigo-200 dark:shadow-none">
              Get Started
            </Button>
          </Link>
        </div>

      </div>
    </div>

    <Fab :href="route('loans.create')" class="bottom-28" />

  </UserMobileLayout>
</template>