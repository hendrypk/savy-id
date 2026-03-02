<script setup lang="ts">
import { 
  BanknotesIcon, 
  CalendarDaysIcon, 
  ClockIcon,
  ShieldCheckIcon,
  ArrowUpRightIcon,
  ReceiptPercentIcon
} from '@heroicons/vue/24/outline';
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import dayjs from 'dayjs'; // Import Day.js
import 'dayjs/locale/id'; // Import locale Indonesia

// Set locale ke Indonesia agar nama bulan dalam bahasa Indonesia
dayjs.locale('id');

interface Transaction {
  uuid: string;
  amount: number;
  created_at: string;
}

interface LoanDetail {
  uuid: string;
  name: string;
  remaining_amount: number;
  credit_limit: number;
  monthly_installment: number;
  status: string;
  due_date: number;
  times_paid: number;
  total_tenor: number;
  transactions: Transaction[];
}

const props = defineProps<{
  loan: LoanDetail;
}>();

const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(value || 0);
};

// Fungsi format tanggal menggunakan Day.js
const formatDate = (dateString: string) => {
  return dayjs(dateString).format('DD MMM YYYY');
};
</script>

<template>
  <Head :title="'Detail ' + loan.name" />

  <UserMobileLayout :title="loan.name" :back-route="route('loans.index')">
    <div class="space-y-6 pb-20">
      
      <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-indigo-200 dark:shadow-none relative overflow-hidden">
        <div class="relative z-10">
          <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-70 mb-2">Sisa Pinjaman</p>
          <h2 class="text-3xl font-black font-mono mb-6">
            {{ formatCurrency(loan.remaining_amount) }}
          </h2>
          
          <div class="grid grid-cols-2 gap-4 border-t border-white/20 pt-6">
            <div>
              <p class="text-[8px] font-black uppercase opacity-60 mb-1">Total Pinjaman</p>
              <p class="text-xs font-bold">{{ formatCurrency(loan.credit_limit) }}</p>
            </div>
            <div class="text-right">
              <p class="text-[8px] font-black uppercase opacity-60 mb-1">Cicilan / Bulan</p>
              <p class="text-xs font-bold">{{ formatCurrency(loan.monthly_installment) }}</p>
            </div>
          </div>
        </div>
        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
      </div>

      <div class="space-y-4 px-1">
        <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Informasi Akun</h3>
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] p-6 space-y-6 shadow-sm">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-slate-50 dark:bg-slate-800 rounded-xl text-slate-400">
                <ShieldCheckIcon class="w-5 h-5" />
              </div>
              <span class="text-xs font-bold text-slate-500">Status Akun</span>
            </div>
            <span class="text-[10px] font-black uppercase text-emerald-500 bg-emerald-50 dark:bg-emerald-500/10 px-3 py-1 rounded-full">
              {{ loan.status }}
            </span>
          </div>

          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-slate-50 dark:bg-slate-800 rounded-xl text-slate-400">
                <CalendarDaysIcon class="w-5 h-5" />
              </div>
              <span class="text-xs font-bold text-slate-500">Tanggal Jatuh Tempo</span>
            </div>
            <span class="text-xs font-black text-slate-800 dark:text-white">Tgl {{ loan.due_date }} / Bln</span>
          </div>

          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-slate-50 dark:bg-slate-800 rounded-xl text-slate-400">
                <ClockIcon class="w-5 h-5" />
              </div>
              <span class="text-xs font-bold text-slate-500">Progress Pembayaran</span>
            </div>
            <span class="text-xs font-black text-indigo-600 dark:text-indigo-400">
                {{ loan.times_paid }} / {{ loan.total_tenor }} <span class="text-[10px] opacity-50 uppercase">Kali</span>
            </span>
          </div>
        </div>
      </div>

      <div class="space-y-4 px-1">
        <div class="flex justify-between items-center px-2">
          <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-400">Riwayat Pembayaran</h3>
          <span v-if="loan.transactions?.length" class="text-[9px] font-bold text-indigo-500 bg-indigo-50 dark:bg-indigo-500/10 px-2 py-0.5 rounded-md">
            {{ loan.transactions.length }} Trx
          </span>
        </div>
        
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] overflow-hidden shadow-sm">
          <div v-if="loan.transactions && loan.transactions.length > 0">
            <div 
              v-for="(trx, index) in loan.transactions" 
              :key="trx.uuid"
              class="flex items-center justify-between p-5 active:bg-slate-50 dark:active:bg-slate-800/50 transition-colors"
              :class="{ 'border-t border-slate-50 dark:border-slate-800/50': index !== 0 }"
            >
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center text-rose-500">
                  <ArrowUpRightIcon class="w-5 h-5 stroke-[2.5]" />
                </div>
                <div>
                  <p class="text-[11px] font-black text-slate-800 dark:text-slate-100 leading-tight">Pembayaran Cicilan</p>
                  <p class="text-[9px] font-medium text-slate-400 mt-1 uppercase">{{ formatDate(trx.created_at) }}</p>
                </div>
              </div>
              <div class="text-right">
                <p class="text-[11px] font-black text-rose-600 dark:text-rose-400 font-mono">
                  -{{ formatCurrency(trx.amount) }}
                </p>
                <p class="text-[7px] font-bold text-slate-300 dark:text-slate-600 uppercase mt-0.5 italic">Berhasil</p>
              </div>
            </div>
          </div>

          <div v-else class="py-14 flex flex-col items-center">
            <div class="w-12 h-12 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-3">
                <ReceiptPercentIcon class="w-6 h-6 text-slate-300" />
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Belum ada cicilan terbayar</p>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-3 px-1 pt-4">
        <Link 
            :href="route('loans.edit', loan.uuid)"
            class="h-14 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 font-black text-[10px] uppercase tracking-widest text-slate-600 dark:text-slate-300 active:scale-95 transition-all flex items-center justify-center"
        >
            Edit Akun
        </Link>
        <button class="h-14 rounded-2xl bg-indigo-600 font-black text-[10px] uppercase tracking-widest text-white shadow-lg shadow-indigo-100 dark:shadow-none active:scale-95 transition-all">
          Bayar Cicilan
        </button>
      </div>

    </div>
  </UserMobileLayout>
</template>