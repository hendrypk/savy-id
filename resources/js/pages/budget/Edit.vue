<script setup lang="ts">
import { 
    ChevronDownIcon, 
    BanknotesIcon, 
    CalendarDaysIcon, 
    TagIcon, 
    PencilSquareIcon 
} from '@heroicons/vue/24/outline';
import { useForm, Head, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import { Button } from '@/components/ui/button';
import DropdownMenu from '@/components/ui/dropdown-menu/DropdownMenu.vue';
import DropdownMenuTrigger from '@/components/ui/dropdown-menu/DropdownMenuTrigger.vue';
import InputGroup from '@/components/ui/input/InputGroup.vue';
import { Label } from '@/components/ui/label';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';

import { confirmDelete, mobileToast } from '@/lib/swal';

// 1. Props
const props = defineProps<{ 
    budget: any,
    categories: { id: number, name: string }[] 
}>();

// 2. Form Initialization
const form = useForm({
    name: props.budget.name, // Pastikan field name ada di database
    transaction_category_id: props.budget.transaction_category_id.toString(),
    plan_amount: props.budget.plan_amount,
    month_year: props.budget.month_year,
});

// 3. Computed for UI Label
const activeCategoryName = computed(() => {
    return props.categories?.find(c => c.id === Number(form.transaction_category_id))?.name || 'Pilih Kategori';
});

const submit = () => {
    form.put(route('budget.update', props.budget.uuid), {
        onSuccess: () => mobileToast('Anggaran diperbarui! 🚀'),
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            mobileToast(firstError || 'Gagal menyimpan anggaran', 'error');
        },
    });
};

const deleteBudget = async (uuid: string) => {
    const result = await confirmDelete('Hapus Anggaran?');
    if (result.isConfirmed) {
        router.delete(route('budget.destroy', uuid), {
            onSuccess: () => mobileToast('Anggaran berhasil dihapus'),
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                mobileToast(firstError || 'Gagal menyimpan anggaran', 'error');
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
            <form @submit.prevent="submit" class="space-y-4">
                
                <InputGroup
                    label="Nama Anggaran"
                    v-model="form.name"
                    :icon="PencilSquareIcon"
                    placeholder="Contoh: Makan Bulanan"
                    :error="form.errors.name"
                />

                <div class="space-y-1.5">
                    <Label class="text-[9px] font-black uppercase text-slate-400 ml-1 flex items-center gap-1.5 tracking-widest">
                        <TagIcon class="w-3 h-3 stroke-[2.5px]" /> Kategori Anggaran
                    </Label>
                    
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button 
                                type="button"
                                class="w-full h-11 px-4 rounded-xl bg-slate-100/50 border border-slate-100 dark:bg-slate-800/50 dark:border-slate-800 flex items-center justify-between text-sm opacity-70 cursor-not-allowed"
                            >
                                <span class="text-slate-900 dark:text-white font-bold">
                                    {{ activeCategoryName }}
                                </span>
                                <ChevronDownIcon class="w-4 h-4 text-slate-400" />
                            </button>
                        </DropdownMenuTrigger>
                    </DropdownMenu>
                    <p class="text-[8px] text-slate-400 ml-2 italic leading-none">* Kategori tidak dapat diubah</p>
                </div>

                <InputGroup
                    label="Nominal Rencana"
                    v-model="form.plan_amount"
                    type="number"
                    prefix="Rp"
                    :icon="BanknotesIcon"
                    placeholder="0"
                    :error="form.errors.plan_amount"
                    input-class="text-lg font-black text-indigo-600 dark:text-indigo-400"
                />

                <InputGroup
                    label="Periode Bulan"
                    v-model="form.month_year"
                    type="month"
                    readonly
                    :icon="CalendarDaysIcon"
                    input-class="opacity-70 cursor-not-allowed bg-slate-100/50"
                />

                <div class="pt-4 space-y-3">
                    <Button 
                        type="submit"
                        :disabled="form.processing" 
                        variant="purple"
                        class="w-full h-12 text-md"
                    >
                        {{ form.processing ? 'Menyimpan...' : 'Update Anggaran' }}
                    </Button>

                    <Button 
                        type="button"
                        variant="ghost"
                        @click="deleteBudget(budget.uuid)" 
                        :disabled="form.processing"
                        class="w-full text-rose-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/20 font-bold"
                    >
                        Hapus Anggaran
                    </Button>
                </div>

                <p class="text-[10px] text-center text-slate-400 font-medium px-4">
                    💡 Perubahan nominal akan langsung mempengaruhi sisa anggaran Anda di bulan ini.
                </p>
            </form>
        </div>
    </UserMobileLayout>
</template>