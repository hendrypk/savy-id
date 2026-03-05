<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';

// Icons
import { 
  PlusIcon, ArrowTrendingUpIcon, ArrowTrendingDownIcon,
  HandRaisedIcon, EyeIcon, EyeSlashIcon,
  ArrowDownLeftIcon, ArrowUpLeftIcon,
  CheckBadgeIcon, ExclamationTriangleIcon
} from '@heroicons/vue/24/outline';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';

// Props dari DashboardController yang baru
interface Props {
  inspiringQuote: { text: string; author: string };
  stats: {
    total_equity: number;
    total_debt: number;
    total_budget: number;
    monthly_savings: number;
    growth_percentage: number;
    budget_usage_percentage: number;
    budget_usage: number;
    analysis: {
      value: number;
      status: 'saving' | 'spending' | 'stable';
      message: string;
    };
  };
  budgets: any[];
  recentTransactions: any[];
  activeLoans: any[];
}

const props = withDefaults(defineProps<Props>(), {
  stats: () => ({ 
    total_equity: 0, total_debt: 0, monthly_savings: 0, growth_percentage: 0, 
    budget_usage_percentage: 0, budget_usage: 0,
    analysis: { value: 0, status: 'stable', message: '' }
  }),
  recentTransactions: () => [],
  activeLoans: () => []
});

// Privacy Logic
const isHidden = ref(false);

const togglePrivacy = () => {
  isHidden.value = !isHidden.value;
  localStorage.setItem('privacy_mode', String(isHidden.value));
};

onMounted(() => {
  isHidden.value = localStorage.getItem('privacy_mode') === 'true';
});

// Formatting Helpers
const formatIDR = (val: number) => {
  return new Intl.NumberFormat('id-ID', { 
    style: 'currency', 
    currency: 'IDR', 
    minimumFractionDigits: 0 
  }).format(val);
};

const displayValue = (val: number) => isHidden.value ? '---------' : formatIDR(val);
</script>

<template>
  <Head title="Ringkasan Keuangan" />

  <UserMobileLayout title="Dashboard">
    <div class="space-y-7 pb-12">
      
      <section class="mb-6 block md:hidden px-1">
        <div class="bg-indigo-50 dark:bg-indigo-900/20 text-indigo-900 dark:text-indigo-200 p-5 rounded-3xl border border-indigo-100/50 dark:border-indigo-800/50 shadow-sm">
          <p class="font-bold text-[11px] text-center leading-relaxed italic">
            {{ inspiringQuote.text }}
          </p>
          <p class="text-[9px] text-center text-gray-400 dark:text-gray-400 mt-2 font-normal">
            — {{ inspiringQuote.author }}
          </p>
        </div>
      </section>

      <section class="px-2">
        <Card>
          <CardContent>
          <div class="relative z-10">
            <div class="flex justify-between items-start mb-2">
              <div class="flex flex-col">
                <span class="text-[10px] font-black uppercase tracking-[0.15em] text-slate-400">
                  Kekayaan Bersih
                </span>
                <div class="flex items-center gap-2 mt-1">
                  <span class="text-[11px] font-bold text-emerald-500">
                    {{ stats.growth_percentage >= 0 ? '+' : '' }}{{ stats.growth_percentage }}%
                  </span>
                  <span class="text-[9px] font-medium text-slate-300">vs bln lalu</span>
                </div>
              </div>
              
              <button 
                @click="togglePrivacy" 
                class="p-2.5 rounded-2xl bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 
                      hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-indigo-500/10 transition-all active:scale-95"
              >
                <component :is="isHidden ? EyeSlashIcon : EyeIcon" class="w-5 h-5" />
              </button>
            </div>
            
            <div class="mb-8">
              <h2 class="text-4xl font-black tracking-tighter text-slate-900 dark:text-white leading-tight">
                {{ displayValue(stats.total_equity) }}
              </h2>
            </div>

            <div class="flex items-center justify-between px-1">
              <div class="flex flex-col">
                <p class="text-[9px] font-black uppercase text-slate-400 mb-1 tracking-wider">Hutang</p>
                <div class="flex items-center gap-1.5">
                  <div class="w-1.5 h-1.5 rounded-full bg-rose-500"></div>
                  <p class="text-xs font-bold text-slate-700 dark:text-slate-200">
                    {{ displayValue(stats.total_debt) }}
                  </p>
                </div>
              </div>

              <div class="h-6 w-[1px] bg-slate-100 dark:bg-slate-800"></div>

              <div class="flex flex-col">
                <p class="text-[9px] font-black uppercase text-slate-400 mb-1 tracking-wider">Budget</p>
                <div class="flex items-center gap-1.5">
                  <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div>
                  <p class="text-xs font-bold text-slate-700 dark:text-slate-200">
                    {{ isHidden ? '••••' : (stats.budget_usage_percentage ?? 0) + '%' }}
                  </p>
                </div>
              </div>

              <div class="h-6 w-[1px] bg-slate-100 dark:bg-slate-800"></div>

              <div class="flex flex-col items-end">
                <p class="text-[9px] font-black uppercase text-slate-400 mb-1 tracking-wider text-right">Tabungan</p>
                <div class="flex items-center gap-1.5">
                  <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                  <p class="text-xs font-bold text-slate-700 dark:text-slate-200">
                    {{ displayValue(stats.monthly_savings) }}
                  </p>
                </div>
              </div>
            </div>
          </div>
          </CardContent>
        </Card>
      </section>

      <section v-if="stats.analysis.status !== 'stable'" class="px-2">
        <Card 
            :class="[
                'rounded-2xl border transition-all duration-500 shadow-sm',
                stats.analysis.status === 'saving' 
                    ? 'bg-emerald-50/80 dark:bg-emerald-500/10 border-emerald-100 dark:border-emerald-500/20' 
                    : 'bg-rose-50/80 dark:bg-rose-500/10 border-rose-100 dark:border-rose-500/20'
            ]"
        >
            <CardContent class="flex items-center gap-4 p-4">
                <div :class="[
                        'w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 shadow-lg dark:shadow-none',
                        stats.analysis.status === 'saving' ? 'bg-emerald-500 shadow-emerald-200' : 'bg-rose-500 shadow-rose-200'
                    ]">
                    <CheckBadgeIcon v-if="stats.analysis.status === 'saving'" class="w-6 h-6 text-white" />
                    <ExclamationTriangleIcon v-else class="w-6 h-6 text-white" />
                </div>
                <div class="flex-1 space-y-0.5">
                    <h4 :class="stats.analysis.status === 'saving' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'" 
                        class="text-[9px] font-black uppercase tracking-widest mb-0.5">
                        Analisis Keuangan
                    </h4>
                    <p class="text-[11px] font-bold text-slate-600 dark:text-slate-300 leading-snug">
                        {{ stats.analysis.message }}
                    </p>
                </div>
            </CardContent>
        </Card>
      </section>

      <section class="px-2">
          <Card>
            <CardContent class="flex justify-around items-center">
              <Link 
                v-for="(action, idx) in [
                  { label: 'Masuk', icon: ArrowTrendingUpIcon, type: 'income', bg: 'bg-emerald-50 dark:bg-emerald-900/20', text: 'text-emerald-600' },
                  { label: 'Keluar', icon: ArrowTrendingDownIcon, type: 'expense', bg: 'bg-rose-50 dark:bg-rose-900/20', text: 'text-rose-600' },
                  { label: 'Pinjam', icon: HandRaisedIcon, link: 'loans.create', bg: 'bg-amber-50 dark:bg-amber-900/20', text: 'text-amber-600' },
                  { label: 'Budget', icon: PlusIcon, link: 'budget.create', bg: 'bg-indigo-50 dark:bg-indigo-900/20', text: 'text-indigo-600' }
                ]"
                :key="idx"
                :href="action.link ? route(action.link) : route('transactions.create', { type: action.type })" 
                class="flex flex-col items-center gap-2 group"
              >
                <div :class="[action.bg, action.text]" class="p-3 rounded-2xl group-active:scale-90 transition-all">
                  <component :is="action.icon" class="w-6 h-6" />
                </div>
                <span class="text-[10px] font-black text-slate-500 uppercase">{{ action.label }}</span>
              </Link>
            </CardContent>
          </Card>
      </section>

      <section class="px-2">
        <Card>
          <CardContent>
            <div class="flex items-center justify-between mb-5 px-1">
              <h3 class="font-black text-slate-400 uppercase text-[9px] tracking-[0.15em]">Aktivitas Terakhir</h3>
              <Link :href="route('transactions.index')" class="text-[9px] font-black uppercase text-indigo-500">Semua</Link>
            </div>

            <div class="space-y-1">
              <div v-for="tx in recentTransactions" :key="tx.id" 
                  class="flex items-center justify-between py-3 active:bg-slate-50 dark:active:bg-slate-800/50 rounded-2xl px-2 transition-colors">
                
                <div class="flex items-center gap-3 min-w-0">
                  <div :class="[
                    'w-10 h-10 rounded-full flex items-center justify-center shrink-0',
                    tx.type === 'in' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10' : 'bg-slate-100 text-slate-600 dark:bg-slate-800'
                  ]">
                    <component :is="tx.type === 'in' ? ArrowDownLeftIcon : ArrowUpLeftIcon" class="w-5 h-5 stroke-[2.5]" />
                  </div>

                  <div class="min-w-0">
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 truncate">{{ tx.title }}</h4>
                    <p class="text-[10px] font-medium text-slate-400 tracking-tight">{{ tx.category }} • {{ tx.date }}</p>
                  </div>
                </div>

                <div class="text-right shrink-0">
                  <p :class="['text-sm font-black', tx.type === 'in' ? 'text-emerald-600' : 'text-slate-900 dark:text-white']">
                    {{ isHidden ? '•••••' : (tx.type === 'in' ? '+' : '-') + formatIDR(tx.amount) }}
                  </p>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>
      </section>
    </div>
  </UserMobileLayout>
</template>