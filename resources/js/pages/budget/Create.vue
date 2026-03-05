<script setup lang="ts">
import { ChevronDownIcon, BanknotesIcon, CalendarDaysIcon, TagIcon, PencilSquareIcon } from '@heroicons/vue/24/outline';
import { useForm, Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import { Button } from '@/components/ui/button';
import DropdownMenu from '@/components/ui/dropdown-menu/DropdownMenu.vue';
import DropdownMenuContent from '@/components/ui/dropdown-menu/DropdownMenuContent.vue';
import DropdownMenuItem from '@/components/ui/dropdown-menu/DropdownMenuItem.vue';
import DropdownMenuTrigger from '@/components/ui/dropdown-menu/DropdownMenuTrigger.vue';
import InputGroup from '@/components/ui/input/InputGroup.vue';
import { Label } from '@/components/ui/label';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import { mobileToast } from '@/lib/swal';

const props = defineProps<{ 
    categories: { id: number, name: string }[] 
}>();

const form = useForm({
    name: '',
    transaction_category_id: '',
    plan_amount: 0,
    month_year: new Date().toISOString().slice(0, 7),
});

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
                                class="w-full h-11 px-4 rounded-xl bg-slate-50/50 border border-slate-100 dark:bg-slate-900/50 dark:border-slate-800 flex items-center justify-between text-sm transition-all active:scale-[0.98]"
                            >
                                <span :class="form.transaction_category_id ? 'text-slate-900 dark:text-white font-bold' : 'text-slate-400'">
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
                                class="rounded-xl font-medium"
                            >
                                {{ cat.name }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <p v-if="form.errors.transaction_category_id" class="text-[9px] text-rose-500 font-bold ml-2 italic leading-none">{{ form.errors.transaction_category_id }}</p>
                </div>

                <InputGroup
                    label="Nominal Rencana"
                    v-model="form.plan_amount"
                    type="number"
                    prefix="Rp"
                    :icon="BanknotesIcon"
                    placeholder="0"
                    :error="form.errors.plan_amount"
                    input-class="text-lg font-black"
                />

                <InputGroup
                    label="Periode Bulan"
                    v-model="form.month_year"
                    type="month"
                    :icon="CalendarDaysIcon"
                    :error="form.errors.month_year"
                />

                <div class="pt-4">
                    <Button 
                        type="submit"
                        :disabled="form.processing" 
                        variant="purple"
                        class="w-full"
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