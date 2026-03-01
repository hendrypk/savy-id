<script setup lang="ts">
// 1. Corrected imports: Added 'router' and fixed 'route'
import { 
  PlusIcon, 
  AdjustmentsHorizontalIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  ArchiveBoxIcon,
  EyeIcon
} from '@heroicons/vue/24/outline';
import { WalletIcon } from '@heroicons/vue/24/solid';
import { Head, Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';

interface Category {
  uuid: string;
  name: string;
  type: string;
  spent_amount: number;
  plan_amount: number;
  remaining: number;
  percentage: number;
}

const props = defineProps<{
  categories: Category[];
  total_planned: number;
  total_spent: number;
  current_month_raw: string; 
  current_month_label: string; 
}>();

// Navigation Logic
const changeMonth = (direction: 'prev' | 'next') => {
    // current_month_raw is "YYYY-MM"
    const [year, month] = props.current_month_raw.split('-').map(Number);
    const date = new Date(year, month - 1, 1);
    
    if (direction === 'prev') {
        date.setMonth(date.getMonth() - 1);
    } else {
        date.setMonth(date.getMonth() + 1);
    }
    
    // Format back to YYYY-MM manually to avoid timezone shifts
    const newYear = date.getFullYear();
    const newMonth = String(date.getMonth() + 1).padStart(2, '0');
    const monthQuery = `${newYear}-${newMonth}`;
    
    router.get(route('budget.index'), { month: monthQuery }, {
        preserveState: true,
        replace: true,
        preserveScroll: true
    });
};

const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(value);
};

const getProgressColor = (percent: number) => {
    if (percent >= 90) return 'bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.4)]';
    if (percent >= 75) return 'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.4)]';
    return 'bg-indigo-600 shadow-[0_0_8px_rgba(79,70,229,0.4)]';
};
</script>

<template>
  <Head title="Anggaran Saya" />

  <UserMobileLayout title="Anggaran">
    <div class="space-y-6 pb-24">
      
      <div class="flex items-center justify-between bg-white dark:bg-slate-800 p-2 rounded-4xl border border-slate-100 dark:border-slate-700 shadow-sm">
        <button 
            @click="changeMonth('prev')"
            class="p-3 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-2xl transition-colors active:scale-90"
        >
            <ChevronLeftIcon class="w-5 h-5 text-slate-400" />
        </button>
        
        <span class="font-black text-slate-700 dark:text-slate-100 uppercase tracking-widest text-xs">
            {{ current_month_label }}
        </span>
        
        <button 
            @click="changeMonth('next')"
            class="p-3 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-2xl transition-colors active:scale-90"
        >
            <ChevronRightIcon class="w-5 h-5 text-slate-400" />
        </button>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div class="bg-indigo-600 p-5 rounded-[2.5rem] shadow-xl shadow-indigo-200 dark:shadow-none">
            <p class="text-[9px] uppercase font-black text-indigo-200 mb-1 tracking-widest">Total Rencana</p>
            <p class="text-sm font-black text-white italic">
                {{ formatCurrency(total_planned || 0) }}
            </p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-5 rounded-[2.5rem] border border-slate-100 dark:border-slate-700">
            <p class="text-[9px] uppercase font-black text-slate-400 mb-1 tracking-widest">Sisa Budget</p>
            <p class="text-sm font-black text-slate-800 dark:text-white italic">
                {{ formatCurrency((total_planned - total_spent) || 0) }}
            </p>
        </div>
      </div>

      <div class="space-y-4">
        <div class="flex justify-between items-center px-1">
            <h3 class="font-black text-xs uppercase tracking-widest text-slate-400">Daftar Anggaran</h3>
            <button class="p-2 text-slate-400 hover:text-indigo-600 transition-colors">
                <AdjustmentsHorizontalIcon class="w-5 h-5" />
            </button>
        </div>

        <div v-if="categories.length === 0" class="flex flex-col items-center justify-center py-12 px-6 bg-slate-50 dark:bg-slate-800/50 rounded-[3rem] border-2 border-dashed border-slate-200 dark:border-slate-700">
            <ArchiveBoxIcon class="w-12 h-12 text-slate-300 mb-4" />
            <p class="text-xs font-black text-slate-400 uppercase tracking-tight text-center">Belum ada kategori anggaran bulan ini</p>
        </div>

        <div 
          v-for="cat in categories" 
          :key="cat.uuid" 
          class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-4xl overflow-hidden shadow-sm shadow-slate-200/50 dark:shadow-none active:scale-[0.98] transition-all"
        >
            <div class="p-4">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-slate-50 dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 rounded-xl">
                            <WalletIcon class="w-5 h-5" />
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-slate-800 dark:text-slate-100 uppercase tracking-tight leading-none mb-1">
                                {{ cat.name }}
                            </h4>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">
                                Limit: {{ formatCurrency(cat.plan_amount) }}
                            </p>
                        </div>
                    </div>
                    <Link 
                        :href="route('budget.show', cat.uuid)" 
                        class="p-2 bg-slate-50 dark:bg-slate-900/50 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-all active:scale-90"
                        title="View Details"
                    >
                        <EyeIcon class="w-5 h-5 stroke-[2.5px]" />
                    </Link>
                </div>

                <div class="relative w-full h-1.5 bg-slate-100 dark:bg-slate-900 rounded-full overflow-hidden mb-2">
                    <div 
                        class="h-full rounded-full transition-all duration-1000 ease-out"
                        :class="getProgressColor(cat.percentage)"
                        :style="{ width: Math.min(cat.percentage, 100) + '%' }"
                    ></div>
                </div>

                <div class="flex justify-between items-center">
                    <div class="flex gap-3">
                        <div class="flex flex-col">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-tighter">Terpakai</span>
                            <span class="text-[10px] font-black text-slate-700 dark:text-slate-200">{{ formatCurrency(cat.spent_amount) }}</span>
                        </div>
                        <div class="w-px h-4 bg-slate-100 dark:bg-slate-700 self-end mb-1"></div>
                        <div class="flex flex-col">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-tighter">Sisa</span>
                            <span 
                                class="text-[10px] font-black"
                                :class="cat.percentage > 90 ? 'text-rose-500' : 'text-emerald-500'"
                            >
                                {{ formatCurrency(cat.remaining) }}
                            </span>
                        </div>
                    </div>
                    
                    <span 
                        class="text-[9px] font-black px-2 py-0.5 rounded-md"
                        :class="cat.percentage > 90 ? 'bg-rose-50 text-rose-600' : 'bg-slate-50 dark:bg-slate-900 text-slate-500'"
                    >
                        {{ cat.percentage }}%
                    </span>
                </div>
            </div>
        </div>

        <Link 
            :href="route('budget.create')"
            class="w-full py-6 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-[2.5rem] flex items-center justify-center gap-3 text-slate-400 dark:text-slate-600 hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-all active:scale-95 group"
        >
            <div class="p-1 bg-slate-100 dark:bg-slate-800 rounded-lg group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                <PlusIcon class="w-5 h-5 stroke-[3px]" />
            </div>
            <span class="text-xs font-black uppercase tracking-widest">Tambah Kategori Anggaran</span>
        </Link>
      </div>
    </div>
  </UserMobileLayout>
</template>