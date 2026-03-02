<script setup lang="ts">
import { 
  PlusIcon, ArrowTrendingUpIcon, ArrowTrendingDownIcon,
  BanknotesIcon, CreditCardIcon, HandRaisedIcon,
} from '@heroicons/vue/24/outline';
import { Head } from '@inertiajs/vue3';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';

// Simply call the function without assigning it to a variable
withDefaults(defineProps<{
    stats: {
        total_equity: number;
        total_debt: number;
        monthly_savings: number;
        growth_percentage: number;
    },
    recentTransactions: Array<any>,
    activeLoans: Array<any>
}>(), {
    stats: () => ({ total_equity: 0, total_debt: 0, monthly_savings: 0, growth_percentage: 0 }),
    recentTransactions: () => [],
    activeLoans: () => []
});

const formatIDR = (val: number) => new Intl.NumberFormat('id-ID', { 
    style: 'currency', currency: 'IDR', minimumFractionDigits: 0 
}).format(val);
</script>

<template>
  <Head title="Ringkasan Keuangan" />

  <UserMobileLayout title="Dashboard">
    <div class="space-y-7 pb-12">
      
      <section>
        <div class="bg-slate-900 dark:bg-indigo-950 rounded-[2.5rem] p-7 text-white shadow-2xl relative overflow-hidden">
          <div class="relative z-10">
            <div class="flex justify-between items-center mb-4">
              <span class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-300">Total Kekayaan Bersih</span>
              <div class="px-2 py-1 bg-green-500/20 text-green-400 rounded-lg text-[10px] font-bold">
                +{{ stats.growth_percentage }}% bln ini
              </div>
            </div>
            <h2 class="text-3xl font-black tracking-tighter mb-6">{{ formatIDR(stats.total_equity) }}</h2>
            
            <div class="grid grid-cols-2 gap-4 border-t border-white/10 pt-5">
              <div>
                <p class="text-[9px] uppercase text-slate-400 font-bold mb-1">Total Hutang</p>
                <p class="text-sm font-extrabold text-rose-400">{{ formatIDR(stats.total_debt) }}</p>
              </div>
              <div class="text-right">
                <p class="text-[9px] uppercase text-slate-400 font-bold mb-1">Total Nabung</p>
                <p class="text-sm font-extrabold text-indigo-300">{{ formatIDR(stats.monthly_savings) }}</p>
              </div>
            </div>
          </div>
          <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-indigo-600/30 rounded-full blur-[50px]"></div>
        </div>
      </section>

      <div class="flex justify-around items-center bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-4 rounded-3xl shadow-sm mx-1">
        <button class="flex flex-col items-center gap-2 group">
          <div class="p-3 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 rounded-2xl group-active:scale-90 transition-all">
            <ArrowTrendingUpIcon class="w-6 h-6" />
          </div>
          <span class="text-[10px] font-black text-slate-500 uppercase">Masuk</span>
        </button>
        <button class="flex flex-col items-center gap-2 group">
          <div class="p-3 bg-rose-50 dark:bg-rose-900/20 text-rose-600 rounded-2xl group-active:scale-90 transition-all">
            <ArrowTrendingDownIcon class="w-6 h-6" />
          </div>
          <span class="text-[10px] font-black text-slate-500 uppercase">Keluar</span>
        </button>
        <button class="flex flex-col items-center gap-2 group">
          <div class="p-3 bg-amber-50 dark:bg-amber-900/20 text-amber-600 rounded-2xl group-active:scale-90 transition-all">
            <HandRaisedIcon class="w-6 h-6" />
          </div>
          <span class="text-[10px] font-black text-slate-500 uppercase">Pinjam</span>
        </button>
        <button class="flex flex-col items-center gap-2 group">
          <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 rounded-2xl group-active:scale-90 transition-all">
            <PlusIcon class="w-6 h-6" />
          </div>
          <span class="text-[10px] font-black text-slate-500 uppercase">Budget</span>
        </button>
      </div>

      <section>
        <h3 class="font-black text-slate-800 dark:text-white uppercase text-xs tracking-widest mb-4 px-2">Pinjaman Aktif</h3>
        <div class="flex gap-4 overflow-x-auto pb-4 no-scrollbar -mx-5 px-5">
          <div v-for="loan in activeLoans" :key="loan.id" 
               class="min-w-40 bg-linear-to-br from-slate-800 to-slate-900 p-5 rounded-4xl text-white flex flex-col justify-between h-40 shadow-xl">
            <div class="bg-white/10 w-fit p-2 rounded-xl"><CreditCardIcon class="w-5 h-5 text-indigo-300" /></div>
            <div>
              <p class="text-[10px] text-slate-400 font-bold mb-1 italic">{{ loan.provider }}</p>
              <p class="text-sm font-black">{{ formatIDR(loan.amount) }}</p>
            </div>
          </div>
          <div class="min-w-25 flex items-center justify-center border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-4xl">
             <PlusIcon class="w-6 h-6 text-slate-300" />
          </div>
        </div>
      </section>

      <section>
        <h3 class="font-black text-slate-800 dark:text-white uppercase text-xs tracking-widest mb-4 px-2">Aktivitas Terakhir</h3>
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 overflow-hidden shadow-sm p-2">
          <div v-for="tx in recentTransactions" :key="tx.id" 
               class="flex items-center justify-between p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
            <div class="flex items-center gap-4">
              <div :class="[
                'p-3 rounded-2xl font-bold text-lg',
                tx.type === 'in' ? 'bg-green-50 text-green-600 dark:bg-green-900/20' : 'bg-slate-50 text-slate-600 dark:bg-slate-800'
              ]">
                <BanknotesIcon v-if="tx.type === 'in'" class="w-5 h-5" />
                <CreditCardIcon v-else class="w-5 h-5" />
              </div>
              <div>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-200 line-clamp-1">{{ tx.title }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ tx.category }} • {{ tx.date }}</p>
              </div>
            </div>
            <p :class="['text-sm font-black', tx.type === 'in' ? 'text-green-600' : 'text-slate-800 dark:text-slate-100']">
              {{ tx.type === 'in' ? '+' : '-' }}{{ (tx.amount/1000).toFixed(0) }}k
            </p>
          </div>
        </div>
      </section>

    </div>
  </UserMobileLayout>
</template>