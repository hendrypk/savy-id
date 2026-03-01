<script setup lang="ts">
import { 
  BanknotesIcon, 
  PlusIcon,
  EllipsisVerticalIcon,
  CalendarDaysIcon 
} from '@heroicons/vue/24/outline';
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js'; 
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';

interface Loan {
    uuid: string;
    name: string;
    remaining_amount: number;
    monthly_installment: number;
    tenor: number | string;
    progress: number;
    status: string;
    due_date?: string;
}

const props = defineProps<{
    loans: Loan[];
}>();

const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(value);
};

// Computed totals untuk keamanan jika data kosong
const totalDebt = props.loans?.reduce((t, l) => t + (l.remaining_amount || 0), 0) || 0;
const totalMonthly = props.loans?.reduce((t, l) => t + (l.monthly_installment || 0), 0) || 0;
</script>

<template>
  <Head title="Pinjaman Saya" />

  <UserMobileLayout title="Pinjaman Saya">
    <div class="space-y-6 pb-20">

      <div class="grid grid-cols-2 gap-3">
        <div class="bg-rose-50 dark:bg-rose-500/10 p-4 rounded-4xl border border-rose-100 dark:border-rose-500/20">
          <p class="text-[10px] uppercase font-black text-rose-400 dark:text-rose-300 mb-1 tracking-widest">Total Hutang</p>
          <p class="text-sm font-black text-rose-600 dark:text-rose-400">
            {{ formatCurrency(totalDebt) }}
          </p>
        </div>

        <div class="bg-indigo-50 dark:bg-indigo-500/10 p-4 rounded-4xl border border-indigo-100 dark:border-indigo-500/20">
          <p class="text-[10px] uppercase font-black text-indigo-400 dark:text-indigo-300 mb-1 tracking-widest">Cicilan / Bln</p>
          <p class="text-sm font-black text-indigo-700 dark:text-indigo-400">
            {{ formatCurrency(totalMonthly) }}
          </p>
        </div>
      </div>

      <div class="space-y-4">
        <div class="flex justify-between items-center px-1">
          <h3 class="font-black text-xs uppercase tracking-widest text-slate-400">Daftar Pinjaman</h3>
        </div>

        <div v-if="loans.length === 0" class="text-center py-10 bg-slate-50 dark:bg-slate-800/50 rounded-4xl border-2 border-dashed border-slate-200 dark:border-slate-700">
            <BanknotesIcon class="w-10 h-10 mx-auto text-slate-300 mb-2" />
            <p class="text-xs font-bold text-slate-400">Belum ada catatan pinjaman.</p>
        </div>

        <div 
          v-else
          v-for="loan in loans" 
          :key="loan.uuid"
          class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-4xl overflow-hidden shadow-sm shadow-slate-200/50 dark:shadow-none"
        >
          <div class="p-5">
            <div class="flex justify-between items-start mb-4">
              <div class="flex items-center gap-3">
                <div class="p-2.5 bg-indigo-600 text-white rounded-2xl shadow-lg shadow-indigo-200 dark:shadow-none">
                  <BanknotesIcon class="w-5 h-5 stroke-[2.5px]" />
                </div>
                <div>
                  <h4 class="text-sm font-black text-slate-800 dark:text-slate-100">
                    {{ loan.name }}
                  </h4>
                  <div class="flex items-center gap-2">
                    <span class="text-[9px] px-2 py-0.5 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 rounded-full font-black uppercase tracking-tighter">
                        {{ loan.status }}
                    </span>
                  </div>
                </div>
              </div>

              <button class="p-1 text-slate-300 hover:text-slate-600 dark:hover:text-slate-100 transition-colors">
                <EllipsisVerticalIcon class="w-5 h-5" />
              </button>
            </div>

            <div class="mb-4">
                <div class="flex justify-between text-[10px] mb-1.5 font-black uppercase tracking-wide">
                    <span class="text-slate-400">Progres Pelunasan</span>
                    <span class="text-indigo-600 dark:text-indigo-400">{{ loan.progress }}%</span>
                </div>
                <div class="w-full h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                  <div 
                    class="h-full bg-indigo-500 rounded-full transition-all duration-1000 ease-out"
                    :style="{ width: loan.progress + '%' }"
                  ></div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 py-3 border-t border-slate-50 dark:border-slate-700/50">
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Sisa Pinjaman</p>
                    <p class="text-xs font-black text-slate-800 dark:text-white">{{ formatCurrency(loan.remaining_amount) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Tenor</p>
                    <p class="text-xs font-black text-slate-800 dark:text-white">{{ loan.tenor }} Bulan</p>
                </div>
            </div>

            <div class="mt-2 flex justify-between items-center p-3 bg-slate-50 dark:bg-slate-900/50 rounded-2xl">
              <div class="flex items-center gap-1.5 text-slate-500 dark:text-slate-400">
                <CalendarDaysIcon class="w-4 h-4" />
                <span class="text-[10px] font-bold">Jatuh tempo: {{ loan.due_date || '-' }}</span>
              </div>
              <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase">
                {{ formatCurrency(loan.monthly_installment) }} / Bln
              </span>
            </div>
          </div>
        </div>

        <Link 
          :href="route('loans.create')"
          class="w-full py-5 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-4xl flex items-center justify-center gap-2 text-slate-400 dark:text-slate-600 hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-all active:scale-95 group"
        >
          <div class="p-1 bg-slate-100 dark:bg-slate-800 rounded-lg group-hover:bg-indigo-600 group-hover:text-white transition-colors">
            <PlusIcon class="w-4 h-4 stroke-[3px]" />
          </div>
          <span class="text-xs font-black uppercase tracking-widest">Tambah Pinjaman Baru</span>
        </Link>

      </div>
    </div>
  </UserMobileLayout>
</template>