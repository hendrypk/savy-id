<script setup lang="ts">
import { ChevronDownIcon, BanknotesIcon, CalendarDaysIcon, TagIcon } from '@heroicons/vue/24/outline';
import { useForm, Head, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import { Button } from '@/components/ui/button';
import DropdownMenu from '@/components/ui/dropdown-menu/DropdownMenu.vue';
import DropdownMenuTrigger from '@/components/ui/dropdown-menu/DropdownMenuTrigger.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';

import { confirmDelete, mobileToast } from '@/lib/swal';

// 1. Props: Receives existing budget data and category list
const props = defineProps<{ 
    budget: any,
    categories: { id: number, name: string }[] 
}>();

// 2. Form Initialization pre-filled with props data
const form = useForm({
    transaction_category_id: props.budget.transaction_category_id.toString(),
    plan_amount: props.budget.plan_amount,
    month_year: props.budget.month_year,
});

// 3. Computed for UI Label
const activeCategoryName = computed(() => {
    return props.categories?.find(c => c.id === Number(form.transaction_category_id))?.name || 'Pilih Kategori';
});

const submit = () => {
    // Using form.put for updates
    form.put(route('budget.update', props.budget.uuid), {
        onSuccess: () => mobileToast('Anggaran diperbarui! 🚀'),
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            mobileToast(firstError || 'Gagal menyimpan anggaran', 'error');
        },
    });
};

const deleteBudget = async (uuid: string) => {
    const result = await confirmDelete('Hapus Kategori?');
    if (result.isConfirmed) {
    router.delete(route('budget.destroy', uuid), {
            onSuccess: () => {
                mobileToast('Kategori berhasil dihapus');
            },
            onFinish: () => {
            },
            preserveScroll: true 
        });
    }
};

</script>

<template>
    <Head title="Edit Anggaran" />

    <UserMobileLayout 
        title="Edit Anggaran" 
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
                                class="w-full h-12 px-4 rounded-2xl bg-slate-100/50 border border-slate-100 dark:bg-slate-800/50 dark:border-slate-700 flex items-center justify-between text-sm transition-all opacity-70 cursor-not-allowed"
                            >
                                <span class="text-slate-900 dark:text-white font-semibold">
                                    {{ activeCategoryName }}
                                </span>
                                <ChevronDownIcon class="w-4 h-4 text-slate-400" />
                            </button>
                        </DropdownMenuTrigger>
                        </DropdownMenu>
                    <p class="text-[9px] text-slate-400 ml-2 italic">*Kategori tidak dapat diubah</p>
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
                            class="rounded-2xl h-14 pl-10 bg-slate-50/50 border-slate-100 dark:bg-slate-800/50 dark:border-slate-700 text-lg font-black text-indigo-600 dark:text-indigo-400" 
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
                        readonly
                        class="rounded-2xl h-12 bg-slate-100/50 border-slate-100 dark:bg-slate-800/50 dark:border-slate-700 font-bold opacity-70" 
                    />
                </div>

                <div class="pt-4 space-y-2">
                    <Button 
                        :disabled="form.processing" 
                        variant="purple" 
                        class="w-full h-14 text-lg"
                    >
                        {{ form.processing ? 'Menyimpan...' : 'Update Anggaran' }}
                    </Button>

                </div>
            </form>
        </div>

            <Button 
                variant="ghost"
                            @click="deleteBudget(budget.uuid)" 
                :disabled="form.processing"
            >
                Hapus Anggaran
            </Button>
    </UserMobileLayout>
</template>

<style scoped>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>