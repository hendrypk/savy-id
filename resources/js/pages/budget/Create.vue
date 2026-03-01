<script setup lang="ts">
import { ChevronDownIcon, BanknotesIcon, CalendarDaysIcon, TagIcon } from '@heroicons/vue/24/outline';
import { useForm, Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import { Button } from '@/components/ui/button';
import DropdownMenu from '@/components/ui/dropdown-menu/DropdownMenu.vue';
import DropdownMenuContent from '@/components/ui/dropdown-menu/DropdownMenuContent.vue';
import DropdownMenuItem from '@/components/ui/dropdown-menu/DropdownMenuItem.vue';
import DropdownMenuTrigger from '@/components/ui/dropdown-menu/DropdownMenuTrigger.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';

import { mobileToast } from '@/lib/swal';

// 1. Props (Categories dikirim dari controller)
const props = defineProps<{ 
    categories: { id: number, name: string }[] 
}>();

// 2. Form Initialization
const form = useForm({
    transaction_category_id: '',
    plan_amount: 0,
    month_year: new Date().toISOString().slice(0, 7), // Format YYYY-MM
});

// 3. Computed for UI Label
const activeCategoryName = computed(() => {
    return props.categories?.find(c => c.id === Number(form.transaction_category_id))?.name || 'Pilih Kategori';
});

const submit = () => {
    form.post(route('budget.store'), {
        onSuccess: () => mobileToast('Anggaran berhasil dibuat! 🎯'),
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            mobileToast(firstError || 'Gagal menyimpan anggaran', 'error');
        },
    });
};
</script>

<template>
    <Head title="Buat Anggaran Baru" />

    <UserMobileLayout 
        title="Buat Anggaran" 
        :back-route="route('budget.index')"
    >
        <div class="p-8 bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm">
            <form @submit.prevent="submit" class="space-y-6">
                
                <div class="space-y-2">
                    <Label class="text-[10px] font-black uppercase text-slate-400 ml-1 flex items-center gap-2">
                        <TagIcon class="w-3 h-3" /> Kategori Anggaran
                    </Label>
                    
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button 
                                type="button"
                                class="w-full h-12 px-4 rounded-2xl bg-slate-50/50 border border-slate-100 dark:bg-slate-800/50 dark:border-slate-700 flex items-center justify-between text-sm transition-all active:scale-[0.98]"
                            >
                                <span :class="form.transaction_category_id ? 'text-slate-900 dark:text-white font-semibold' : 'text-slate-400'">
                                    {{ activeCategoryName }}
                                </span>
                                <ChevronDownIcon class="w-4 h-4 text-slate-400" />
                            </button>
                        </DropdownMenuTrigger>

                        <DropdownMenuContent align="start" class="w-(--reka-dropdown-menu-trigger-width) rounded-2xl p-2 shadow-xl border-slate-100 dark:border-slate-800">
                            <DropdownMenuItem 
                                v-for="cat in categories" 
                                :key="cat.id"
                                @click="form.transaction_category_id = cat.id.toString()"
                            >
                                {{ cat.name }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <p v-if="form.errors.transaction_category_id" class="text-[10px] text-rose-500 font-bold ml-2">{{ form.errors.transaction_category_id }}</p>
                </div>

                <div class="space-y-2">
                    <Label class="text-[10px] font-black uppercase text-slate-400 ml-1 flex items-center gap-2">
                        <BanknotesIcon class="w-3 h-3" /> Nominal Rencana
                    </Label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">Rp</div>
                        <Input 
                            type="number" 
                            v-model="form.plan_amount" 
                            placeholder="0"
                            class="rounded-2xl h-14 pl-10 bg-slate-50/50 border-slate-100 dark:bg-slate-800/50 dark:border-slate-700 text-lg font-black" 
                        />
                    </div>
                    <p v-if="form.errors.plan_amount" class="text-[10px] text-rose-500 font-bold ml-2">{{ form.errors.plan_amount }}</p>
                </div>

                <div class="space-y-2">
                    <Label class="text-[10px] font-black uppercase text-slate-400 ml-1 flex items-center gap-2">
                        <CalendarDaysIcon class="w-3 h-3" /> Periode Bulan
                    </Label>
                    <Input 
                        type="month" 
                        v-model="form.month_year" 
                        class="rounded-2xl h-12 bg-slate-50/50 border-slate-100 dark:bg-slate-800/50 dark:border-slate-700 font-bold" 
                    />
                    <p v-if="form.errors.month_year" class="text-[10px] text-rose-500 font-bold ml-2">{{ form.errors.month_year }}</p>
                </div>

                <div class="pt-4">
                    <Button 
                        :disabled="form.processing" 
                        variant="purple" 
                    >
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Anggaran' }}
                    </Button>
                </div>

                <p class="text-[10px] text-center text-slate-400 font-medium px-4">
                    💡 Sistem akan otomatis memantau pengeluaran Anda berdasarkan kategori ini selama bulan berjalan.
                </p>
            </form>
        </div>
    </UserMobileLayout>
</template>

<style scoped>
/* Menghilangkan spinner pada input number agar lebih bersih */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>