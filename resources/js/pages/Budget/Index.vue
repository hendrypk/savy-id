<script setup lang="ts">
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
  PlusIcon, 
  EllipsisVerticalIcon, 
  AdjustmentsHorizontalIcon 
} from '@heroicons/vue/24/outline';
import { WalletIcon } from '@heroicons/vue/24/solid';

const props = defineProps<{
  categories: Array<any>
}>();

const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(value);
};
</script>

<template>
  <Head title="Anggaran" />

  <UserMobileLayout title="Anggaran Saya">
    <div class="space-y-6">
      
      <div class="flex items-center justify-between bg-white p-2 rounded-2xl border border-slate-100 shadow-sm">
        <button class="p-2 hover:bg-slate-50 rounded-xl">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <span class="font-bold text-slate-700">Februari 2026</span>
        <button class="p-2 hover:bg-slate-50 rounded-xl">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div class="bg-indigo-50 p-4 rounded-2xl border border-indigo-100">
            <p class="text-[10px] uppercase font-bold text-indigo-400 mb-1">Total Rencana</p>
            <p class="text-sm font-extrabold text-indigo-700">Rp 8.500.000</p>
        </div>
        <div class="bg-emerald-50 p-4 rounded-2xl border border-emerald-100">
            <p class="text-[10px] uppercase font-bold text-emerald-400 mb-1">Tersedia</p>
            <p class="text-sm font-extrabold text-emerald-700">Rp 2.100.000</p>
        </div>
      </div>

      <div class="space-y-3">
        <div class="flex justify-between items-center">
            <h3 class="font-extrabold text-slate-800">Kategori Anggaran</h3>
            <button class="p-2 bg-slate-100 rounded-lg text-slate-600">
                <AdjustmentsHorizontalIcon class="w-4 h-4" />
            </button>
        </div>

        <div v-for="cat in categories" :key="cat.uuid" class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
            <div class="p-4">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-100 text-indigo-600 rounded-xl">
                            <WalletIcon class="w-5 h-5" />
                        </div>
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-700">{{ cat.name }}</h4>
                            <p class="text-[10px] text-slate-400 uppercase font-bold">{{ cat.type }}</p>
                        </div>
                    </div>
                    <button class="text-slate-300">
                        <EllipsisVerticalIcon class="w-5 h-5" />
                    </button>
                </div>

                <div class="flex justify-between text-xs mb-2">
                    <span class="text-slate-500 font-medium">Terpakai: <span class="text-slate-800 font-bold">{{ formatCurrency(cat.spent_amount) }}</span></span>
                    <span class="text-slate-400 italic">Sisa {{ formatCurrency(cat.remaining) }}</span>
                </div>

                <div class="w-full h-2 bg-slate-100 rounded-full">
                    <div 
                        class="h-full rounded-full transition-all duration-700"
                        :class="cat.percentage > 90 ? 'bg-rose-500' : 'bg-indigo-500'"
                        :style="{ width: cat.percentage + '%' }"
                    ></div>
                </div>
            </div>
        </div>

        <button class="w-full py-4 border-2 border-dashed border-slate-200 rounded-2xl flex items-center justify-center gap-2 text-slate-400 hover:bg-slate-50 transition-colors">
            <PlusIcon class="w-5 h-5" />
            <span class="text-sm font-bold">Tambah Kategori</span>
        </button>
      </div>
    </div>
  </UserMobileLayout>
</template>