<script setup lang="ts">
import { ChevronLeftIcon } from '@heroicons/vue/24/outline';
import { Head, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';

// Define types untuk form agar lebih type-safe
interface LoanForm {
    name: string;
    loan_type: 'credit_card' | 'personal_loan' | 'paylater';
    credit_limit: number | '';
    interest_rate_monthly: number | '';
    due_date: number | '';
}

const form = useForm<LoanForm>({
    name: '',
    loan_type: 'credit_card',
    credit_limit: '',
    interest_rate_monthly: '',
    due_date: '',
});

// Perbaikan: Jangan panggil window langsung di template
const goBack = () => {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        // Fallback jika tidak ada history (misal user lgsg buka link create)
        window.location.href = route('loans.index');
    }
};

const submit = () => {
    form.post(route('loans.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Tambah Akun Kredit" />

    <UserMobileLayout title="Tambah Kredit">
        <div class="max-w-md mx-auto space-y-8">
            
            <div class="flex items-center gap-4">
                <button 
                    type="button"
                    @click="goBack" 
                    class="p-2.5 bg-white rounded-2xl shadow-sm border border-slate-100 hover:bg-slate-50 transition-colors active:scale-90"
                >
                    <ChevronLeftIcon class="w-5 h-5 text-slate-600" />
                </button>
                <div>
                    <h3 class="font-black text-slate-800 text-lg tracking-tight">Akun Baru</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Informasi Pinjaman</p>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black uppercase text-slate-400 ml-1">Nama Akun / Bank</label>
                    <input 
                        v-model="form.name" 
                        type="text" 
                        placeholder="Contoh: BCA Everyday Card" 
                        class="form-input"
                        :class="{'border-red-500 focus:ring-red-500': form.errors.name}"
                    >
                    <Transition name="fade">
                        <p v-if="form.errors.name" class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ form.errors.name }}</p>
                    </Transition>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[11px] font-black uppercase text-slate-400 ml-1">Tipe Kredit</label>
                    <div class="relative">
                        <select v-model="form.loan_type" class="form-input appearance-none">
                            <option value="credit_card">💳 Kartu Kredit</option>
                            <option value="personal_loan">💰 Pinjaman Personal</option>
                            <option value="paylater">📱 Paylater</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                            <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" /></svg>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-black uppercase text-slate-400 ml-1">Limit (Rp)</label>
                        <input 
                            v-model.number="form.credit_limit" 
                            type="number" 
                            placeholder="5000000" 
                            class="form-input"
                            :class="{'border-red-500': form.errors.credit_limit}"
                        >
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-black uppercase text-slate-400 ml-1">Bunga (% bln)</label>
                        <input 
                            v-model.number="form.interest_rate_monthly" 
                            type="number" 
                            step="0.01" 
                            placeholder="1.75" 
                            class="form-input"
                            :class="{'border-red-500': form.errors.interest_rate_monthly}"
                        >
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[11px] font-black uppercase text-slate-400 ml-1">Tanggal Jatuh Tempo</label>
                    <div class="flex items-center bg-white border border-slate-100 rounded-2xl px-4 focus-within:ring-2 focus-within:ring-indigo-500 transition-all shadow-sm">
                        <span class="text-slate-400 text-sm font-bold">Tgl.</span>
                        <input 
                            v-model.number="form.due_date" 
                            type="number" 
                            min="1" 
                            max="31" 
                            placeholder="15" 
                            class="w-full border-none focus:ring-0 p-4 text-sm font-semibold"
                        >
                    </div>
                    <p class="text-[9px] text-slate-400 italic mt-1 ml-1">* Masukkan angka antara 1 sampai 31</p>
                </div>

                <div class="pt-6">
                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="w-full bg-slate-900 text-white p-5 rounded-2xl font-black shadow-xl shadow-slate-200 active:scale-95 hover:bg-black transition-all disabled:opacity-50 disabled:active:scale-100 flex justify-center items-center gap-3"
                    >
                        <span v-if="form.processing" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                        {{ form.processing ? 'Memproses...' : 'Simpan Akun' }}
                    </button>
                </div>
            </form>
        </div>
    </UserMobileLayout>
</template>
