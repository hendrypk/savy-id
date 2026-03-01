<script setup lang="ts">
import { 
    ArrowUpCircleIcon, 
    ArrowDownCircleIcon,
    CheckIcon,
    TagIcon, 
    HomeIcon, 
    ShoppingBagIcon, 
    GiftIcon, 
    AcademicCapIcon, 
    BanknotesIcon, 
    TruckIcon, 
    CreditCardIcon
} from '@heroicons/vue/24/outline';
import { Head, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import { mobileToast } from '@/lib/swal';
// Menggunakan Heroicons 24 Outline

const availableIcons = [
    { name: 'TagIcon', component: TagIcon },
    { name: 'HomeIcon', component: HomeIcon },
    { name: 'ShoppingBagIcon', component: ShoppingBagIcon },
    { name: 'GiftIcon', component: GiftIcon },
    { name: 'AcademicCapIcon', component: AcademicCapIcon },
    { name: 'BanknotesIcon', component: BanknotesIcon },
    { name: 'TruckIcon', component: TruckIcon },
    { name: 'CreditCardIcon', component: CreditCardIcon },
];

const getSelectedIcon = () => {
    return availableIcons.find(i => i.name === form.icon)?.component || TagIcon;
};

const presetColors = [
    '#4f46e5', '#f43f5e', '#10b981', '#f59e0b', 
    '#8b5cf6', '#ec4899', '#06b6d4', '#475569'
];

const form = useForm({
    name: '',
    type: 'expense' as 'income' | 'expense',
    color: '#4f46e5',
    icon: 'TagIcon',
});

const submit = () => {
    form.post(route('transaction-categories.store'), {
        preserveScroll: true,
        onSuccess: () => {
            mobileToast('Kategori berhasil dibuat', 'success');
            form.reset();
        },

        onError: () => {
            mobileToast('Gagal membuat kategori', 'error');
        },
    });
};
</script>

<template>
    <Head title="Buat Kategori Baru" />

    <UserMobileLayout 
        title="Kategori Baru"
        :back-route="route('transaction-categories.index')"
    >
        <div class="space-y-8 pb-10">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 p-6">
                <form @submit.prevent="submit" class="space-y-7">
                    
                    <div class="flex flex-col items-center justify-center p-6 rounded-4xl border-2 border-dashed border-slate-100 dark:border-slate-800 mb-2">
                        <div 
                            :style="{ backgroundColor: form.color + '20', color: form.color }"
                            class="w-16 h-16 rounded-2xl flex items-center justify-center mb-3 transition-all duration-300"
                        >
                            <component :is="getSelectedIcon()" class="w-8 h-8 stroke-[2px]" />
                        </div>
                        <div class="text-center space-y-1">
                            <h2 
                                class="text-xl font-black tracking-tight transition-colors duration-300"
                                :class="form.name ? 'text-slate-800 dark:text-slate-100' : 'text-slate-300'"
                            >
                                {{ form.name || 'Nama Kategori' }}
                            </h2>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label for="name" class="text-[11px] font-black uppercase text-slate-400 ml-1">Nama Kategori</Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            class="h-12 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 focus:ring-indigo-500 shadow-none transition-all"
                            placeholder="Misal: Makan Siang, Gaji..."
                            required
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="space-y-2">
                        <Label class="text-[11px] font-black uppercase text-slate-400 ml-1">Jenis Transaksi</Label>
                        <div class="flex gap-3">
                            <button 
                                type="button"
                                @click="form.type = 'expense'"
                                :class="[
                                    'flex-1 flex items-center justify-center gap-2 py-3.5 rounded-2xl border transition-all font-bold text-sm',
                                    form.type === 'expense' 
                                        ? 'bg-rose-50 border-rose-200 text-rose-600 dark:bg-rose-950/20 dark:border-rose-900/50' 
                                        : 'bg-white border-slate-100 text-slate-400 dark:bg-slate-900 dark:border-slate-800'
                                ]"
                            >
                                <ArrowUpCircleIcon class="w-5 h-5" /> Pengeluaran
                            </button>
                            <button 
                                type="button"
                                @click="form.type = 'income'"
                                :class="[
                                    'flex-1 flex items-center justify-center gap-2 py-3.5 rounded-2xl border transition-all font-bold text-sm',
                                    form.type === 'income' 
                                        ? 'bg-emerald-50 border-emerald-200 text-emerald-600 dark:bg-emerald-950/20 dark:border-emerald-900/50' 
                                        : 'bg-white border-slate-100 text-slate-400 dark:bg-slate-900 dark:border-slate-800'
                                ]"
                            >
                                <ArrowDownCircleIcon class="w-5 h-5" /> Pemasukan
                            </button>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <Label class="text-[11px] font-black uppercase text-slate-400 ml-1">Pilih Ikon</Label>
                        <div class="grid grid-cols-4 gap-3">
                            <button
                                v-for="icon in availableIcons"
                                :key="icon.name"
                                type="button"
                                @click="form.icon = icon.name"
                                :class="[
                                    'h-12 rounded-xl flex items-center justify-center transition-all active:scale-90',
                                    form.icon === icon.name 
                                        ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' 
                                        : 'bg-slate-50 text-slate-400 dark:bg-slate-800'
                                ]"
                            >
                                <component :is="icon.component" class="w-6 h-6" />
                            </button>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <Label class="text-[11px] font-black uppercase text-slate-400 ml-1">Pilih Warna</Label>
                        <div class="grid grid-cols-4 gap-3">
                            <button
                                v-for="color in presetColors"
                                :key="color"
                                type="button"
                                @click="form.color = color"
                                :style="{ backgroundColor: color }"
                                class="h-10 rounded-xl flex items-center justify-center transition-transform active:scale-90"
                            >
                                <CheckIcon v-if="form.color === color" class="w-5 h-5 text-white stroke-[3.5px]" />
                            </button>
                        </div>
                    </div>

                    <div class="pt-4">
                        <Button 
                            type="submit" 
                            variant="purple" 
                            :disabled="form.processing"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Kategori' }}
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </UserMobileLayout>
</template>