<script setup lang="ts">
import { 
  AdjustmentsHorizontalIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  ArchiveBoxIcon,
  EyeIcon,
  ChevronDownIcon,
  CheckBadgeIcon,
  ExclamationTriangleIcon
} from '@heroicons/vue/24/outline';
import { WalletIcon } from '@heroicons/vue/24/solid';
import { Head, Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import Fab from '@/components/ui/button/Fab.vue';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import { ref, computed } from 'vue';

interface Budget {
  uuid: string;
  name: string;
  spent_amount: number;
  plan_amount: number;
  remaining: number;
  percentage: number;
  category_name: string;
}

const props = defineProps<{
  budgets: Budget[];
  total_planned: number;
  total_spent: number;
  usage_pct: number; // Added from new backend
  analysis: {        // Added from new backend
    value: number;
    status: 'saving' | 'spending' | 'stable';
    message: string;
  };
  current_month_raw: string; 
  current_month_label: string; 
}>();

// Navigation & Date Logic
const monthInputRef = ref<HTMLInputElement | null>(null);

const triggerPicker = () => {
    monthInputRef.value?.showPicker?.();
};

const changeMonth = (direction: 'prev' | 'next') => {
    const [year, month] = props.current_month_raw.split('-').map(Number);
    const date = new Date(year, month - 1, 1);
    direction === 'prev' ? date.setMonth(date.getMonth() - 1) : date.setMonth(date.getMonth() + 1);
    
    const monthQuery = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
    router.get(route('budget.index'), { month: monthQuery }, { preserveState: true, replace: true });
};

const handleDateChange = (e: Event) => {
    const val = (e.target as HTMLInputElement).value;
    if (val) router.get(route('budget.index'), { month: val }, { preserveState: true });
};

// UI Helpers
const formatCurrency = (v: number) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(v);

const getProgressColor = (percent: number) => {
    if (percent >= 90) return 'bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.4)]';
    if (percent >= 75) return 'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.4)]';
    return 'bg-indigo-600 shadow-[0_0_8px_rgba(79,70,229,0.4)]';
};

const daysRemaining = computed(() => {
  const now = new Date();
  const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0).getDate();
  return Math.max(0, lastDay - now.getDate());
});
</script>

<template>
  <Head title="My Budgets" />

  <UserMobileLayout title="Budgets">
    <div class="space-y-5 pb-24 px-1">
      
      <Card class="overflow-hidden rounded-[2rem] border-slate-100 dark:border-slate-800 shadow-sm">
        <CardContent class="p-0">
            <div class="flex items-center justify-between p-2 border-b border-slate-50 dark:border-slate-800">
                <button @click="changeMonth('prev')" class="p-3 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-2xl active:scale-90 transition-all">
                    <ChevronLeftIcon class="w-5 h-5 text-slate-400" />
                </button>
                <div @click="triggerPicker" class="relative flex-1 flex items-center justify-center gap-2 py-2 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-2xl active:scale-95 cursor-pointer">
                    <span class="font-black text-slate-700 dark:text-slate-100 uppercase tracking-widest text-[10px] flex items-center gap-2">
                        {{ current_month_label }}
                        <ChevronDownIcon class="w-3.5 h-3.5 text-indigo-500" />
                    </span>
                    <input ref="monthInputRef" type="month" :value="current_month_raw" @change="handleDateChange" class="absolute inset-0 opacity-0 pointer-events-none" />
                </div>
                <button @click="changeMonth('next')" class="p-3 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-2xl active:scale-90 transition-all">
                    <ChevronRightIcon class="w-5 h-5 text-slate-400" />
                </button>
            </div>

            <div class="grid grid-cols-2 divide-x divide-slate-50 dark:divide-slate-800 border-b border-slate-50 dark:border-slate-800">
                <div class="p-5 text-center">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1.5">Total Planned</p>
                    <p class="text-sm font-black text-slate-800 dark:text-white italic">{{ formatCurrency(total_planned) }}</p>
                </div>
                <div class="p-5 text-center">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1.5">Remaining</p>
                    <p 
                        class="text-sm font-black italic transition-colors duration-300"
                        :class="(total_planned - total_spent) < 0 ? 'text-rose-500' : 'text-emerald-500'"
                    >
                        {{ formatCurrency(total_planned - total_spent) }}
                    </p>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50/30 dark:bg-slate-800/20">
                <div class="flex justify-between items-end mb-2">
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Overall Usage</span>
                    <div class="flex items-baseline gap-1">
                        <span class="text-xs font-black italic" :class="usage_pct > 90 ? 'text-rose-500' : 'text-indigo-600 dark:text-indigo-400'">
                            {{ usage_pct }}%
                        </span>
                        <span class="text-[8px] font-bold text-slate-400 uppercase">{{ daysRemaining }} Days Left</span>
                    </div>
                </div>
                <div class="w-full h-1.5 bg-slate-200/50 dark:bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-1000 ease-out"
                        :class="getProgressColor(usage_pct)"
                        :style="{ width: Math.min(usage_pct, 100) + '%' }"
                    ></div>
                </div>
            </div>
        </CardContent>
      </Card>

      <Card 
        v-if="analysis.status !== 'stable'"
        :class="[
            'rounded-[2rem] border transition-all duration-500',
            analysis.status === 'saving' 
                ? 'bg-emerald-50/80 dark:bg-emerald-500/10 border-emerald-100/50 dark:border-emerald-500/20' 
                : 'bg-rose-50/80 dark:bg-rose-500/10 border-rose-100/50 dark:border-rose-500/20'
        ]"
      >
        <CardContent class="flex items-center gap-4 p-4">
            <div :class="[
                    'w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 shadow-lg dark:shadow-none',
                    analysis.status === 'saving' ? 'bg-emerald-500 shadow-emerald-200' : 'bg-rose-500 shadow-rose-200'
                ]">
                <CheckBadgeIcon v-if="analysis.status === 'saving'" class="w-6 h-6 text-white" />
                <ExclamationTriangleIcon v-else class="w-6 h-6 text-white" />
            </div>
            <div class="flex-1">
                <h4 :class="analysis.status === 'saving' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'" 
                    class="text-[9px] font-black uppercase tracking-widest mb-0.5">
                    Financial Analysis
                </h4>
                <p class="text-[11px] font-bold text-slate-600 dark:text-slate-300 leading-snug">
                    {{ analysis.message }}
                </p>
            </div>
        </CardContent>
      </Card>

      <div class="space-y-4">
        <div class="flex justify-between items-center px-1">
            <h3 class="font-black text-xs uppercase tracking-widest text-slate-400">Budget List</h3>
            <button class="p-2 text-slate-400 hover:text-indigo-600 transition-colors">
                <AdjustmentsHorizontalIcon class="w-5 h-5" />
            </button>
        </div>

        <div v-if="budgets.length === 0" class="py-20 flex flex-col items-center text-center px-10 bg-white dark:bg-slate-900 rounded-[3rem] border border-dashed border-slate-200 dark:border-slate-800">
            <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-[2rem] flex items-center justify-center mb-6 border border-slate-100 dark:border-slate-800">
                <ArchiveBoxIcon class="w-7 h-7 text-slate-300" />
            </div>
            <h3 class="font-black text-slate-800 dark:text-slate-100 text-sm">No budgets this month</h3>
            <p class="text-[9px] text-slate-400 mt-2 font-bold uppercase tracking-widest leading-relaxed">
                Take control of your money. Create your first budget allocation now.
            </p>
        </div>

        <div v-for="item in budgets" :key="item.uuid" 
          class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-[2.5rem] overflow-hidden shadow-sm active:scale-[0.98] transition-all"
        >
            <div class="p-5">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-slate-50 dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 rounded-xl">
                            <WalletIcon class="w-5 h-5" />
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-slate-800 dark:text-slate-100 tracking-tight leading-none mb-1">{{ item.name }}</h4>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">
                                Target: {{ formatCurrency(item.plan_amount) }}
                            </p>
                        </div>
                    </div>
                    <Link :href="route('budget.show', item.uuid)" class="p-2 bg-slate-50 dark:bg-slate-900/50 text-slate-400 hover:text-indigo-600 rounded-xl transition-all active:scale-90">
                        <EyeIcon class="w-5 h-5 stroke-[2.5px]" />
                    </Link>
                </div>

                <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-900 rounded-full overflow-hidden mb-3">
                    <div class="h-full rounded-full transition-all duration-1000 ease-out"
                        :class="getProgressColor(item.percentage)"
                        :style="{ width: Math.min(item.percentage, 100) + '%' }"
                    ></div>
                </div>

                <div class="flex justify-between items-center">
                    <div class="flex gap-4">
                        <div class="flex flex-col">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Spent</span>
                            <span class="text-[10px] font-black text-slate-700 dark:text-slate-200">{{ formatCurrency(item.spent_amount) }}</span>
                        </div>
                        <div class="flex flex-col border-l border-slate-100 dark:border-slate-700 pl-4">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Left</span>
                            <span class="text-[10px] font-black" :class="item.percentage > 90 ? 'text-rose-500' : 'text-emerald-500'">
                                {{ formatCurrency(item.remaining) }}
                            </span>
                        </div>
                    </div>
                    <span class="text-[9px] font-black px-2.5 py-1 rounded-full"
                        :class="item.percentage > 90 ? 'bg-rose-50 text-rose-600' : 'bg-slate-50 dark:bg-slate-900 text-slate-500'">
                        {{ item.percentage }}%
                    </span>
                </div>
            </div>
        </div>
      </div>
    </div>

    <Fab :href="route('budget.create')" class="bottom-28 shadow-indigo-200" />
  </UserMobileLayout>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>