<script setup lang="ts">
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
  PlusIcon,
  EllipsisVerticalIcon 
} from '@heroicons/vue/24/outline';
import { 
  BanknotesIcon,
  CalendarDaysIcon 
} from '@heroicons/vue/24/solid';

const props = defineProps<{
  loans: Array<any>
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
  <Head title="Loans" />

  <UserMobileLayout title="Pinjaman Saya">
    <div class="space-y-6">

      <!-- Summary Card -->
      <div class="grid grid-cols-2 gap-3">
        <div class="bg-rose-50 p-4 rounded-2xl border border-rose-100">
          <p class="text-[10px] uppercase font-bold text-rose-400 mb-1">Total Hutang</p>
          <p class="text-sm font-extrabold text-rose-600">
            {{ formatCurrency(loans.reduce((t, l) => t + l.remaining_amount, 0)) }}
          </p>
        </div>

        <div class="bg-indigo-50 p-4 rounded-2xl border border-indigo-100">
          <p class="text-[10px] uppercase font-bold text-indigo-400 mb-1">Cicilan Bulan Ini</p>
          <p class="text-sm font-extrabold text-indigo-700">
            {{ formatCurrency(loans.reduce((t, l) => t + l.monthly_installment, 0)) }}
          </p>
        </div>
      </div>

      <!-- Loan List -->
      <div class="space-y-3">
        <div class="flex justify-between items-center">
          <h3 class="font-extrabold text-slate-800">Daftar Pinjaman</h3>
        </div>

        <div 
          v-for="loan in loans" 
          :key="loan.uuid"
          class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm"
        >
          <div class="p-4">

            <!-- Header -->
            <div class="flex justify-between items-start mb-3">
              <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-100 text-indigo-600 rounded-xl">
                  <BanknotesIcon class="w-5 h-5" />
                </div>
                <div>
                  <h4 class="text-sm font-extrabold text-slate-700">
                    {{ loan.name }}
                  </h4>
                  <p class="text-[10px] text-slate-400 uppercase font-bold">
                    {{ loan.status }}
                  </p>
                </div>
              </div>

              <button class="text-slate-300">
                <EllipsisVerticalIcon class="w-5 h-5" />
              </button>
            </div>

            <!-- Info -->
            <div class="flex justify-between text-xs mb-2">
              <span class="text-slate-500 font-medium">
                Sisa: 
                <span class="text-slate-800 font-bold">
                  {{ formatCurrency(loan.remaining_amount) }}
                </span>
              </span>
              <span class="text-slate-400 italic">
                {{ loan.tenor }} bulan
              </span>
            </div>

            <!-- Progress -->
            <div class="w-full h-2 bg-slate-100 rounded-full mb-3">
              <div 
                class="h-full bg-indigo-500 rounded-full transition-all duration-700"
                :style="{ width: loan.progress + '%' }"
              ></div>
            </div>

            <!-- Installment Info -->
            <div class="flex justify-between items-center text-xs">
              <div class="flex items-center gap-1 text-slate-500">
                <CalendarDaysIcon class="w-4 h-4" />
                <span>Jatuh tempo {{ loan.due_date }}</span>
              </div>
              <span class="font-bold text-indigo-600">
                {{ formatCurrency(loan.monthly_installment) }} / bulan
              </span>
            </div>

          </div>
        </div>

        <!-- Add Loan Button -->
<Link 
  :href="route('loans.create')"
  class="w-full py-4 border-2 border-dashed border-slate-200 rounded-2xl flex items-center justify-center gap-2 text-slate-400 hover:bg-slate-50 transition-colors"
>
  <PlusIcon class="w-5 h-5" />
  <span class="text-sm font-bold">Tambah Pinjaman</span>
</Link>

      </div>
    </div>
  </UserMobileLayout>
</template>